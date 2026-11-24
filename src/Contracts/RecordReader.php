<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

interface RecordReader
{
    public function readPage(ReadRequest $request): RecordPage;
}
