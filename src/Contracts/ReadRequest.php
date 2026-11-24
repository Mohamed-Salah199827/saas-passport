<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

use InvalidArgumentException;

final readonly class ReadRequest
{
    public function __construct(
        public string $entity,
        public int $limit,
        public ?string $cursor = null,
    ) {
        if (trim($entity) === '' || $limit < 1) {
            throw new InvalidArgumentException('A read request requires an entity and a positive limit.');
        }
    }
}
