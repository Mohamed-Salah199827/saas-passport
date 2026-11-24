<?php

declare(strict_types=1);

namespace SaaSPassport\Core;

use SaaSPassport\Contracts\ReadRequest;
use SaaSPassport\Contracts\Record;
use SaaSPassport\Contracts\RecordReader;
use SaaSPassport\Exceptions\PassportException;

final readonly class PagedRecords
{
    public function __construct(private int $pageSize = 100, private int $pageLimit = 10000)
    {
        if ($pageSize < 1 || $pageLimit < 1) {
            throw new \InvalidArgumentException('Pagination limits must be positive.');
        }
    }

    /**
     * @return iterable<Record>
     */
    public function read(RecordReader $source, string $entity): iterable
    {
        $cursor = null;
        $seen = [];
        for ($number = 1; $number <= $this->pageLimit; $number++) {
            $page = $source->readPage(new ReadRequest($entity, $this->pageSize, $cursor));
            if (count($page->records) > $this->pageSize) {
                throw new PassportException('The source returned more records than requested.');
            }

            yield from $page->records;
            if ($page->nextCursor === null) {
                return;
            }

            if (isset($seen[$page->nextCursor])) {
                throw new PassportException('The source returned a repeated cursor.');
            }

            $cursor = $page->nextCursor;
            $seen[$cursor] = true;
        }

        throw new PassportException('The configured source page limit was exceeded.');
    }
}
