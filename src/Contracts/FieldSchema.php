<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

use InvalidArgumentException;

final readonly class FieldSchema
{
    public function __construct(
        public string $key,
        public FieldType $type,
        public bool $nullable,
        public bool $required,
        public ?string $targetEntity = null,
    ) {
        if (trim($key) === '' || ($targetEntity !== null && trim($targetEntity) === '')) {
            throw new InvalidArgumentException('Schema keys must be nonempty.');
        }

        if (($type === FieldType::Reference) !== ($targetEntity !== null)) {
            throw new InvalidArgumentException('Reference fields require exactly one target entity.');
        }
    }
}
