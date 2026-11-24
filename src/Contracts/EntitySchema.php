<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

use InvalidArgumentException;

final readonly class EntitySchema
{
    /** @var list<FieldSchema> */
    public array $fields;

    /** @param array<array-key, mixed> $fields */
    public function __construct(public string $key, array $fields)
    {
        if (trim($key) === '') {
            throw new InvalidArgumentException('Schema keys must be nonempty.');
        }

        if (!array_is_list($fields)) {
            throw new InvalidArgumentException('Schema collections must be lists.');
        }

        $seen = [];
        $validated = [];
        foreach ($fields as $item) {
            if (!$item instanceof FieldSchema || isset($seen[$item->key])) {
                throw new InvalidArgumentException('Schema items must have unique keys.');
            }

            $seen[$item->key] = true;
            $validated[] = $item;
        }
        $this->fields = $validated;
    }
}
