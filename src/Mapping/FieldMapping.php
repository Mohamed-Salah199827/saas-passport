<?php

declare(strict_types=1);

namespace SaaSPassport\Mapping;

use SaaSPassport\Exceptions\PassportException;
use stdClass;

final readonly class FieldMapping
{
    /** @param array<string, string> $fields Source field to target field. */
    public function __construct(public string $targetEntity, public array $fields)
    {
        if (trim($targetEntity) === '' || count(array_unique($fields)) !== count($fields)) {
            throw new PassportException('A mapping requires an entity and unique target fields.');
        }
        foreach ($fields as $source => $target) {
            if (trim((string) $source) === '' || trim($target) === '') {
                throw new PassportException('Mapped field keys must be nonempty.');
            }
        }
    }

    /** @return array<string, bool|string|null> */
    public function apply(stdClass $values): array
    {
        $mapped = [];
        foreach ($this->fields as $source => $target) {
            $key = (string) $source;
            if (!property_exists($values, $key)) {
                continue;
            }

            $value = $values->{$key};
            if ($value !== null && !is_bool($value) && !is_string($value)) {
                throw new PassportException('A mapped field has an unsupported value type.');
            }
            $mapped[$target] = $value;
        }
        return $mapped;
    }
}
