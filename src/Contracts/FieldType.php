<?php

declare(strict_types=1);

namespace SaaSPassport\Contracts;

enum FieldType: string
{
    case String = 'string';
    case Boolean = 'boolean';
    case Integer = 'integer';
    case Decimal = 'decimal';
    case DateTime = 'datetime';
    case Reference = 'reference';
    case Opaque = 'opaque';
}
