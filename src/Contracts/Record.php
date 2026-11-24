<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

use InvalidArgumentException;

final readonly class Record
{
    /**
     * @param array<string, bool|string|null> $fields
     * @param array<string, list<RecordReference>|null> $relationships
     */
    public function __construct(
        public string $id,
        public array $fields,
        public array $relationships = [],
    ) {
        if ($id === '') {
            throw new InvalidArgumentException('A record ID must be nonempty.');
        }
    }

    /** @return array{id: string, fields: object, relationships: object} */
    public function envelope(): array
    {
        return [
            'id' => $this->id,
            'fields' => (object) $this->fields,
            'relationships' => (object) $this->relationships,
        ];
    }
}
