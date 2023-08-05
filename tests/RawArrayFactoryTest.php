<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\UserArrayFactory;

/**
 * @internal
 */
final class RawArrayFactoryTest extends TestCase
{
    public function testMakeInstance(): void
    {
        // Act
        $user = UserArrayFactory::new()->make();

        // Assert
        self::assertIsArray($user);
    }
}
