<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

use InvalidArgumentException;

final readonly class ConnectorSchema
{
    /** @var list<EntitySchema> */
    public array $entities;

    /** @param array<array-key, mixed> $entities */
    public function __construct(array $entities)
    {
        if (!array_is_list($entities)) {
            throw new InvalidArgumentException('Schema collections must be lists.');
        }

        $seen = [];
        $validated = [];
        foreach ($entities as $item) {
            if (!$item instanceof EntitySchema || isset($seen[$item->key])) {
                throw new InvalidArgumentException('Schema items must have unique keys.');
            }

            $seen[$item->key] = true;
            $validated[] = $item;
        }
        $this->entities = $validated;

        foreach ($this->entities as $entity) {
            foreach ($entity->fields as $field) {
                if ($field->targetEntity !== null && !isset($seen[$field->targetEntity])) {
                    throw new InvalidArgumentException('Reference target is absent from the schema.');
                }
            }
        }
    }
}
