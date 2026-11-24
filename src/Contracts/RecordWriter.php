<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

interface RecordWriter
{
    /**
     * Persist the operation once within the caller's destination scope.
     *
     * Implementations must deduplicate repeated keys and return the same destination ID.
     * Throw on failure or uncertainty. Never return an ID without an acknowledged write.
     *
     * @param array<string, bool|string|null> $fields
     */
    public function writeRecord(string $entity, array $fields, string $idempotencyKey): string;
}
