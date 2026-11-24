<?php

declare(strict_types=1);

namespace SaaSPassport\Core;

use SaaSPassport\Contracts\RecordReader;
use SaaSPassport\Contracts\SchemaDiscovery;

interface Source extends SchemaDiscovery, RecordReader
{
}
