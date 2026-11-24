<?php

declare(strict_types=1);

namespace SaaSPassport\Archive;

use SaaSPassport\Exceptions\PassportException;
use stdClass;

final class ArchiveIntegrity
{
    public const MAX_METADATA_BYTES = 4_194_304;
    public const MAX_RECORD_BYTES = 1_048_576;
    private const MAX_FILES = 4096;
    private const MAX_ARCHIVE_BYTES = 68_719_476_736;

    /** @return array<string, string> Entity to record path. */
    public function verify(string $root): array
    {
        $canonical = realpath($root);
        if ($canonical === false || !is_dir($canonical) || is_link($root)) {
            throw new PassportException('The archive root must be a directory.');
        }

        $manifest = $this->metadata($canonical, 'manifest.json');
        if (
            ($manifest->formatVersion ?? null) !== '1.0'
            || !is_array($manifest->files ?? null)
            || count($manifest->files) > self::MAX_FILES
        ) {
            throw new PassportException('The archive manifest version or file list is invalid.');
        }

        $recordsDirectory = $canonical . DIRECTORY_SEPARATOR . 'records';
        if (
            is_link($recordsDirectory)
            || realpath($recordsDirectory) !== $recordsDirectory
            || !is_dir($recordsDirectory)
        ) {
            throw new PassportException('The archive records directory is invalid.');
        }

        $paths = ['manifest.json' => true];
        $entities = [];
        $totalBytes = 0;
        foreach ($manifest->files as $entry) {
            if (!$entry instanceof stdClass) {
                throw new PassportException('An archive descriptor is invalid.');
            }
            $path = $this->descriptorPath($entry);
            if (isset($paths[$path])) {
                throw new PassportException('The archive contains a duplicate file descriptor.');
            }

            $bytes = $entry->bytes;
            if (!is_int($bytes)) {
                throw new PassportException('The archive file size is invalid.');
            }
            $totalBytes += $bytes;
            if ($totalBytes > self::MAX_ARCHIVE_BYTES) {
                throw new PassportException('The archive exceeded the aggregate byte limit.');
            }

            $file = $this->file($canonical, $path);
            clearstatcache(true, $file);
            if (@filesize($file) !== $entry->bytes || @hash_file('sha256', $file) !== $entry->sha256) {
                throw new PassportException('An archive file failed its byte integrity check.');
            }

            if (str_starts_with($path, 'records/')) {
                $entity = $entry->entity ?? null;
                $expected = $entry->records ?? null;
                if (!is_string($entity) || $entity === '' || isset($entities[$entity]) || !is_int($expected)) {
                    throw new PassportException('An archive stream descriptor is invalid.');
                }
                $count = 0;
                foreach ($this->records($file) as $_record) {
                    $count++;
                }
                if ($count !== $expected) {
                    throw new PassportException('An archive stream record count is invalid.');
                }
                $entities[$entity] = $path;
            } else {
                $this->metadata($canonical, $path);
            }
            $paths[$path] = true;
        }

        $this->checkLayout($canonical, $paths);
        return $entities;
    }

    /**
     * Stream envelopes from a file in an archive already verified and under the caller's control.
     *
     * @return iterable<stdClass>
     */
    public function records(string $file): iterable
    {
        $stream = @fopen($file, 'rb');
        if ($stream === false) {
            throw new PassportException('An archive record stream could not be opened.');
        }
        try {
            while (($line = fgets($stream, self::MAX_RECORD_BYTES + 1)) !== false) {
                if (!str_ends_with($line, "\n") || str_ends_with($line, "\r\n")) {
                    throw new PassportException('An archive record has invalid framing or exceeds its byte limit.');
                }

                $record = $this->object($line);
                if (
                    !is_string($record->id ?? null)
                    || $record->id === ''
                    || !($record->fields ?? null) instanceof stdClass
                    || !($record->relationships ?? null) instanceof stdClass
                ) {
                    throw new PassportException('An archive record envelope is invalid.');
                }

                yield $record;
            }

            if (!feof($stream)) {
                throw new PassportException('An archive record stream could not be read.');
            }
        } finally {
            fclose($stream);
        }
    }

    private function descriptorPath(stdClass $entry): string
    {
        $path = $entry->path ?? null;
        $bytes = $entry->bytes ?? null;
        $hash = $entry->sha256 ?? null;
        if (
            !is_string($path)
            || (
                !in_array($path, ['schema.json', 'coverage.json'], true)
                && preg_match('~\Arecords/[a-z0-9][a-z0-9_-]{0,63}\.jsonl\z~', $path) !== 1
            )
            || preg_match('~\Arecords/(?:con|prn|aux|nul|com[1-9]|lpt[1-9])\.~', $path) === 1
            || !is_int($bytes)
            || $bytes < 0
            || $bytes > 9_007_199_254_740_991
            || !is_string($hash)
            || preg_match('/\A[0-9a-f]{64}\z/', $hash) !== 1
        ) {
            throw new PassportException('An archive descriptor has invalid path, size, or digest metadata.');
        }

        return $path;
    }

    private function metadata(string $root, string $path): stdClass
    {
        $file = $this->file($root, $path);
        $json = @file_get_contents($file, false, null, 0, self::MAX_METADATA_BYTES + 1);
        if ($json === false || strlen($json) > self::MAX_METADATA_BYTES) {
            throw new PassportException('Archive metadata exceeded its byte limit or could not be read.');
        }

        return $this->object($json);
    }

    private function object(string $json): stdClass
    {
        try {
            $value = json_decode($json, false, 64, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new PassportException('Archive content contains invalid JSON.');
        }
        if (!$value instanceof stdClass) {
            throw new PassportException('Archive content must contain a JSON object.');
        }
        return $value;
    }

    private function file(string $root, string $path): string
    {
        $file = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
        if (is_link($file) || realpath($file) !== $file || !is_file($file)) {
            throw new PassportException('An archive file is missing or redirects outside its declared path.');
        }
        return $file;
    }

    /** @param array<string, true> $paths */
    private function checkLayout(string $root, array $paths): void
    {
        $records = $root . DIRECTORY_SEPARATOR . 'records';
        if (!isset($paths['schema.json'], $paths['coverage.json'])) {
            throw new PassportException('The archive directory structure is invalid.');
        }

        foreach (new \FilesystemIterator($root) as $entry) {
            $name = $entry instanceof \SplFileInfo ? $entry->getFilename() : basename($entry);
            if ($name !== 'records' && !isset($paths[$name])) {
                throw new PassportException('The archive contains an unlisted entry.');
            }
        }
        foreach (new \FilesystemIterator($records) as $entry) {
            $name = $entry instanceof \SplFileInfo ? $entry->getFilename() : basename($entry);
            if (!isset($paths['records/' . $name])) {
                throw new PassportException('The archive contains an unlisted record entry.');
            }
        }
    }
}
