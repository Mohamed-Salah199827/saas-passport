<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

use InvalidArgumentException;

final readonly class RecordPage
{
    /** @var list<Record> */
    public array $records;

    /** @param array<array-key, mixed> $records */
    public function __construct(array $records, public ?string $nextCursor)
    {
        if (!array_is_list($records)) {
            throw new InvalidArgumentException('A page must contain a list of records.');
        }
        foreach ($records as $record) {
            if (!$record instanceof Record) {
                throw new InvalidArgumentException('A page must contain only records.');
            }
        }
        $this->records = $records;
    }
}
