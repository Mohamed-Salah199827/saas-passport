<?php

declare(strict_types=1);

namespace SaaSPassport\Migration;

use SaaSPassport\Archive\ArchiveIntegrity;
use SaaSPassport\Contracts\RecordWriter;
use SaaSPassport\Exceptions\PassportException;
use SaaSPassport\Mapping\FieldMapping;
use SaaSPassport\Verification\DestinationLookup;
use stdClass;

final readonly class ArchiveImport
{
    private const MAX_ENTITY_RECORDS = 100_000;
    private const MAX_IDENTITY_BYTES = 8_388_608;

    public function __construct(private ArchiveIntegrity $integrity = new ArchiveIntegrity())
    {
    }

    /**
     * Validate and count the planned scalar writes without a destination writer.
     *
     * @param array<string, FieldMapping> $mappings Source entity to mapping.
     * @return array<string, int> Planned record counts by source entity.
     */
    public function preview(string $archive, array $mappings): array
    {
        $counts = [];
        $streams = $this->integrity->verify($archive);
        foreach ($this->operations($archive, $mappings, $streams) as [$entity]) {
            $counts[$entity] = ($counts[$entity] ?? 0) + 1;
        }
        return $counts;
    }

    /** @param array<string, FieldMapping> $mappings */
    public function execute(string $archive, array $mappings, RecordWriter $writer): int
    {
        $streams = $this->integrity->verify($archive);
        foreach ($this->operations($archive, $mappings, $streams) as $_operation) {
            // Validate every mapped record before the destination receives any writes.
        }

        $written = 0;
        foreach ($this->operations($archive, $mappings, $streams) as [, $entity, $fields, $key]) {
            if ($writer->writeRecord($entity, $fields, $key) === '') {
                throw new PassportException('The destination did not acknowledge a record ID.');
            }
            $written++;
        }
        return $written;
    }

    /**
     * Compare only the mapped scalar fields. An unavailable record remains unverified.
     *
     * @param array<string, FieldMapping> $mappings
     * @return array{verified: int, mismatched: int, unverified: int}
     */
    public function verifyDestination(string $archive, array $mappings, DestinationLookup $destination): array
    {
        $counts = ['verified' => 0, 'mismatched' => 0, 'unverified' => 0];
        $streams = $this->integrity->verify($archive);
        foreach ($this->operations($archive, $mappings, $streams) as [, $entity, $fields, $key]) {
            $actual = $destination->readWrittenRecord($entity, $key);
            if ($actual === null) {
                $counts['unverified']++;
                continue;
            }

            $comparable = array_intersect_key($actual, $fields);
            ksort($comparable, SORT_STRING);
            $counts[$comparable === $fields ? 'verified' : 'mismatched']++;
        }
        return $counts;
    }

    /**
     * @param array<string, FieldMapping> $mappings
     * @param array<string, string> $streams
     * @return iterable<array{string, string, array<string, bool|string|null>, string}>
     */
    private function operations(string $archive, array $mappings, array $streams): iterable
    {
        $manifestDigest = @hash_file('sha256', rtrim($archive, '/\\') . '/manifest.json');
        if ($manifestDigest === false) {
            throw new PassportException('The verified archive manifest could not be read.');
        }

        foreach ($mappings as $entity => $mapping) {
            if (!isset($streams[$entity])) {
                throw new PassportException('A mapped source entity is absent from the archive.');
            }

            $path = rtrim($archive, '/\\') . '/' . $streams[$entity];
            $seen = [];
            $identityBytes = 0;
            foreach ($this->integrity->records($path) as $record) {
                $id = $record->id;
                $fields = $record->fields;
                $relationships = $record->relationships;
                if (
                    !is_string($id)
                    || !$fields instanceof stdClass
                    || !$relationships instanceof stdClass
                    || (array) $relationships !== []
                ) {
                    throw new PassportException('Scalar import cannot process record relationships.');
                }

                if (isset($seen[$id])) {
                    throw new PassportException('A mapped stream contains a duplicate record ID.');
                }
                $identityBytes += strlen($id);
                if (count($seen) >= self::MAX_ENTITY_RECORDS || $identityBytes > self::MAX_IDENTITY_BYTES) {
                    throw new PassportException('A mapped stream exceeded the scalar import identity limit.');
                }
                $seen[$id] = true;

                $values = $mapping->apply($fields);
                ksort($values, SORT_STRING);
                $key = hash('sha256', serialize([$manifestDigest, $entity, $id, $mapping->targetEntity, $values]));
                yield [(string) $entity, $mapping->targetEntity, $values, $key];
            }
        }
    }
}
