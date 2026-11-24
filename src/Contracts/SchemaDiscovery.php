<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

interface SchemaDiscovery
{
    public function discoverSchema(): ConnectorSchema;
}
