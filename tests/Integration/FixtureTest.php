<?php

declare(strict_types=1);

namespace SaaSPassport\Tests\Integration;

use PHPUnit\Framework\TestCase;

final class FixtureTest extends TestCase
{
    public function testSyntheticFixtureCanBeReadFromCheckout(): void
    {
        $line = file_get_contents(__DIR__ . '/../Fixtures/contacts.jsonl');
        self::assertIsString($line);
        $record = json_decode($line, true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(
            ['id' => '0001', 'fields' => ['name' => 'Example Contact'], 'relationships' => []],
            $record,
        );
    }
}
