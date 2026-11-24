<?php

declare(strict_types=1);

namespace SaaSPassport\Verification;

interface DestinationLookup
{
    /**
     * Return comparable scalar values for an acknowledged operation, or null when unavailable.
     *
     * @return array<string, bool|string|null>|null
     */
    public function readWrittenRecord(string $entity, string $idempotencyKey): ?array;
}
