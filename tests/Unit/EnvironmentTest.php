<?php

declare(strict_types=1);

namespace SaaSPassport\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SaaSPassport\Exceptions\PassportException;

final class EnvironmentTest extends TestCase
{
    public function testPackageExceptionIsAvailableThroughComposer(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Synthetic package failure');

        throw new PassportException('Synthetic package failure');
    }
}
