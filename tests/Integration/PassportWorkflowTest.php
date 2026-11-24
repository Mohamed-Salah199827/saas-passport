<?php

declare(strict_types=1);

namespace SaaSPassport\Tests\Integration;

use PHPUnit\Framework\TestCase;
use SaaSPassport\Archive\ArchiveExporter;
use SaaSPassport\Archive\ArchiveIntegrity;
use SaaSPassport\Contracts\ConnectorSchema;
use SaaSPassport\Contracts\EntitySchema;
use SaaSPassport\Contracts\FieldSchema;
use SaaSPassport\Contracts\FieldType;
use SaaSPassport\Contracts\ReadRequest;
use SaaSPassport\Contracts\Record;
use SaaSPassport\Contracts\RecordPage;
use SaaSPassport\Contracts\RecordWriter;
use SaaSPassport\Core\Source;
use SaaSPassport\Exceptions\PassportException;
use SaaSPassport\Mapping\FieldMapping;
use SaaSPassport\Migration\ArchiveImport;
use SaaSPassport\Verification\DestinationLookup;

final class PassportWorkflowTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/passport-test-' . bin2hex(random_bytes(12));
        self::assertTrue(mkdir($this->directory, 0700));
    }

    protected function tearDown(): void
    {
        $entries = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($entries as $entry) {
            if ($entry instanceof \SplFileInfo) {
                $entry->isDir() ? rmdir($entry->getPathname()) : unlink($entry->getPathname());
            }
        }
        rmdir($this->directory);
    }

    public function testExportPreviewAndReplay(): void
    {
        $archive = $this->directory . '/archive';
        (new ArchiveExporter())->export($this->source(), $archive, 'synthetic', 'fixture');
        $mapping = ['contacts' => new FieldMapping('people', ['name' => 'fullName', 'note' => 'memo'])];
        $import = new ArchiveImport();
        $writer = new class implements RecordWriter, DestinationLookup {
            /** @var array<string, array<string, bool|string|null>> */
            private array $writes = [];

            /** @return list<array<string, bool|string|null>> */
            public function writtenFields(): array
            {
                return array_values($this->writes);
            }

            /** @return array<string, bool|string|null>|null */
            public function readWrittenRecord(string $entity, string $idempotencyKey): ?array
            {
                return $this->writes[$idempotencyKey] ?? null;
            }

            public function writeRecord(string $entity, array $fields, string $idempotencyKey): string
            {
                $this->writes[$idempotencyKey] ??= $fields;

                return $idempotencyKey;
            }
        };

        self::assertSame(['contacts' => 2], $import->preview($archive, $mapping));
        self::assertSame([], $writer->writtenFields());
        self::assertSame(
            ['verified' => 0, 'mismatched' => 0, 'unverified' => 2],
            $import->verifyDestination($archive, $mapping, $writer),
        );
        $first = $import->execute($archive, $mapping, $writer);
        $replay = $import->execute($archive, $mapping, $writer);
        self::assertSame([2, 2], [$first, $replay]);
        self::assertSame(
            ['verified' => 2, 'mismatched' => 0, 'unverified' => 0],
            $import->verifyDestination($archive, $mapping, $writer),
        );
        self::assertSame(
            [['fullName' => 'Example'], ['fullName' => 'Another', 'memo' => null]],
            $writer->writtenFields(),
        );
    }

    public function testTamperedArchiveIsRejected(): void
    {
        $archive = $this->directory . '/archive';
        (new ArchiveExporter())->export($this->source(), $archive, 'synthetic', 'fixture');
        file_put_contents($archive . '/records/stream-0.jsonl', '{}', FILE_APPEND);

        $this->expectException(PassportException::class);
        $this->expectExceptionMessage('byte integrity check');
        (new ArchiveIntegrity())->verify($archive);
    }

    public function testArchiveRejectsAnUnlistedEntry(): void
    {
        $archive = $this->directory . '/archive';
        (new ArchiveExporter())->export($this->source(), $archive, 'synthetic', 'fixture');
        file_put_contents($archive . '/records/unlisted.jsonl', '');

        $this->expectException(PassportException::class);
        $this->expectExceptionMessage('unlisted record entry');
        (new ArchiveIntegrity())->verify($archive);
    }

    public function testRepeatedCursorDoesNotPublishPartialArchive(): void
    {
        $archive = $this->directory . '/archive';

        try {
            (new ArchiveExporter())->export($this->source(true), $archive, 'synthetic', 'fixture');
            self::fail('The source repeated a cursor.');
        } catch (PassportException $exception) {
            self::assertStringContainsString('repeated cursor', $exception->getMessage());
            self::assertDirectoryDoesNotExist($archive);
            self::assertSame([], glob($this->directory . '/*', GLOB_NOSORT));
        }
    }

    public function testImportRejectsDuplicateIdentitiesBeforeWriting(): void
    {
        $archive = $this->directory . '/archive';
        (new ArchiveExporter())->export($this->source(duplicate: true), $archive, 'synthetic', 'fixture');
        $writer = $this->createMock(RecordWriter::class);
        $writer->expects(self::never())->method('writeRecord');

        $this->expectException(PassportException::class);
        $this->expectExceptionMessage('duplicate record ID');
        $mapping = ['contacts' => new FieldMapping('people', ['name' => 'name'])];
        (new ArchiveImport())->execute($archive, $mapping, $writer);
    }

    private function source(bool $repeat = false, bool $duplicate = false): Source
    {
        return new class ($repeat, $duplicate) implements Source {
            public function __construct(private readonly bool $repeat, private readonly bool $duplicate)
            {
            }

            public function discoverSchema(): ConnectorSchema
            {
                return new ConnectorSchema([new EntitySchema('contacts', [
                    new FieldSchema('name', FieldType::String, false, true),
                    new FieldSchema('note', FieldType::String, true, false),
                ])]);
            }

            public function readPage(ReadRequest $request): RecordPage
            {
                if ($request->cursor === null) {
                    return new RecordPage([new Record('0001', ['name' => 'Example'])], 'next');
                }
                return new RecordPage(
                    [new Record($this->duplicate ? '0001' : '0002', ['name' => 'Another', 'note' => null])],
                    $this->repeat ? 'next' : null,
                );
            }
        };
    }
}
