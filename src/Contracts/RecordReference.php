<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

use InvalidArgumentException;

final readonly class RecordReference
{
    public function __construct(public string $entity, public string $id)
    {
        if (trim($entity) === '' || $id === '') {
            throw new InvalidArgumentException('A record reference requires an entity and ID.');
        }
    }
}
