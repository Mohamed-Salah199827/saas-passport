<?php

declare(strict_types=1);

namespace SaaSPassport\Archive;

use DateTimeImmutable;
use DateTimeZone;
use SaaSPassport\Contracts\ConnectorSchema;
use SaaSPassport\Contracts\EntitySchema;
use SaaSPassport\Contracts\FieldSchema;
use SaaSPassport\Core\PagedRecords;
use SaaSPassport\Core\Source;
use SaaSPassport\Exceptions\PassportException;

final readonly class ArchiveExporter
{
    public function __construct(private PagedRecords $pages = new PagedRecords())
    {
    }

    public function export(Source $source, string $destination, string $provider, string $sourceKey): void
    {
        if (trim($provider) === '' || trim($sourceKey) === '') {
            throw new PassportException('The archive source descriptor must be nonempty.');
        }

        $parent = realpath(dirname($destination));
        $name = basename($destination);
        if (
            $parent === false
            || !is_dir($parent)
            || preg_match('/\A[a-zA-Z0-9][a-zA-Z0-9._-]*\z/', $name) !== 1
            || str_ends_with($name, '.')
        ) {
            throw new PassportException('The archive requires an existing destination parent.');
        }

        $destination = $parent . DIRECTORY_SEPARATOR . $name;
        $lock = @fopen($destination . '.passport-lock', 'x');
        if ($lock === false) {
            throw new PassportException('The archive destination could not be reserved.');
        }

        $stage = $parent . DIRECTORY_SEPARATOR . '.passport-' . bin2hex(random_bytes(16));
        $created = false;
        try {
            if ($this->exists($destination) || !@mkdir($stage, 0700)) {
                throw new PassportException('The archive destination must be new and writable.');
            }

            $created = true;
            if (!@mkdir($stage . '/records', 0700)) {
                throw new PassportException('The archive staging directory is not writable.');
            }

            $this->writeArchive($source, $stage, $provider, $sourceKey);
            if ($this->exists($destination) || !@rename($stage, $destination)) {
                throw new PassportException('The completed archive could not be published.');
            }

            $created = false;
        } finally {
            if ($created) {
                if (is_dir($stage . '/records')) {
                    foreach (new \FilesystemIterator($stage . '/records') as $file) {
                        if ($file instanceof \SplFileInfo) {
                            @unlink($file->getPathname());
                        }
                    }
                    @rmdir($stage . '/records');
                }
                foreach (['manifest.json', 'schema.json', 'coverage.json'] as $path) {
                    if (is_file($stage . '/' . $path)) {
                        @unlink($stage . '/' . $path);
                    }
                }
                @rmdir($stage);
            }
            fclose($lock);
            @unlink($destination . '.passport-lock');
        }
    }

    private function exists(string $destination): bool
    {
        clearstatcache(true, $destination);
        return file_exists($destination) || is_link($destination);
    }

    private function writeArchive(Source $source, string $stage, string $provider, string $sourceKey): void
    {
        $schema = $source->discoverSchema();
        $files = [$this->writeJson($stage, 'schema.json', $this->schema($schema))];
        $coverage = [];

        foreach ($schema->entities as $index => $entity) {
            $path = 'records/stream-' . $index . '.jsonl';
            $stream = @fopen($stage . '/' . $path, 'xb');
            if ($stream === false) {
                throw new PassportException('The record stream could not be created.');
            }
            $count = 0;
            try {
                foreach ($this->pages->read($source, $entity->key) as $record) {
                    $line = $this->encode($record->envelope()) . "\n";
                    if (
                        strlen($line) > ArchiveIntegrity::MAX_RECORD_BYTES
                        || fwrite($stream, $line) !== strlen($line)
                    ) {
                        throw new PassportException('A record exceeded the byte limit or could not be written.');
                    }
                    $count++;
                }
            } finally {
                fclose($stream);
            }

            $files[] = $this->describe($stage, $path) + ['entity' => $entity->key, 'records' => $count];
            $coverage[] = ['key' => $entity->key, 'status' => 'complete', 'reasons' => []];
        }

        $files[] = $this->writeJson($stage, 'coverage.json', ['consistency' => 'best-effort', 'entities' => $coverage]);
        $this->writeJson($stage, 'manifest.json', [
            'formatVersion' => '1.0',
            'archiveId' => $this->uuid(),
            'createdAt' => (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s\Z'),
            'source' => ['provider' => $provider, 'key' => $sourceKey],
            'files' => $files,
        ]);
        (new ArchiveIntegrity())->verify($stage);
    }

    /** @return array<string, mixed> */
    private function schema(ConnectorSchema $schema): array
    {
        return ['entities' => array_map(static fn (EntitySchema $entity): array => [
            'key' => $entity->key,
            'fields' => array_map(static fn (FieldSchema $field): array => [
                'key' => $field->key,
                'type' => $field->type->value,
                'nullable' => $field->nullable,
                'required' => $field->required,
            ] + ($field->targetEntity === null ? [] : ['targetEntity' => $field->targetEntity]), $entity->fields),
        ], $schema->entities)];
    }

    /** @param array<string, mixed> $data
     * @return array{path: string, bytes: int, sha256: string}
     */
    private function writeJson(string $root, string $path, array $data): array
    {
        $bytes = $this->encode($data) . "\n";
        if (strlen($bytes) > ArchiveIntegrity::MAX_METADATA_BYTES) {
            throw new PassportException('Archive metadata exceeded the byte limit.');
        }
        if (@file_put_contents($root . '/' . $path, $bytes) !== strlen($bytes)) {
            throw new PassportException('Archive metadata could not be written.');
        }
        return $this->describe($root, $path);
    }

    /** @return array{path: string, bytes: int, sha256: string} */
    private function describe(string $root, string $path): array
    {
        $size = @filesize($root . '/' . $path);
        $hash = @hash_file('sha256', $root . '/' . $path);
        if ($size === false || $hash === false) {
            throw new PassportException('An archive file could not be read.');
        }
        return ['path' => $path, 'bytes' => $size, 'sha256' => $hash];
    }

    private function encode(mixed $value): string
    {
        try {
            return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\JsonException) {
            throw new PassportException('An archive value could not be encoded.');
        }
    }

    private function uuid(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
