<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\UserFactory;
use Tests\Helpers\UserProfile;
use Tests\Helpers\UserRole;

/**
 * @internal
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class FactoryTest extends TestCase
{
    public function testMake_makeOneUser(): void
    {
        // Arrange
        $name = 'john';
        $expectedName = 'john (admin)';
        $expectedRole = UserRole::ADMIN;

        // Act
        $user = UserFactory::new()->admin()->make(['name' => $name]);

        // Assert
        self::assertSame($expectedName, $user->name());
        self::assertSame($expectedRole, $user->role());
    }

    public function testMakeMany_makeArrayOfUsers(): void
    {
        // Arrange
        $states = [['email' => 'foo'], ['email' => 'bar']];

        // Act
        $users = UserFactory::new()->makeMany($states);

        // Assert
        self::assertCount(count($states), $users);

        foreach ($users as $index => $user) {
            self::assertSame($states[$index]['email'], $user->email);
        }
    }

    public function testMakeOne_makeOneUser(): void
    {
        // Arrange
        $name = 'john';
        $expectedName = 'john (admin)';
        $expectedRole = UserRole::ADMIN;

        // Act
        $user = UserFactory::new()->admin()->makeOne(['name' => $name]);

        // Assert
        self::assertSame($expectedName, $user->name());
        self::assertSame($expectedRole, $user->role());
    }

    public function testMake_makeMultiple(): void
    {
        // Arrange
        $name = 'john';
        $expectedName = 'john (admin)';
        $expectedRole = UserRole::ADMIN;
        $expectedCount = 5;

        // Act
        $users = UserFactory::new()->count($expectedCount)->admin()->make(['name' => $name]);

        // Assert
        self::assertCount($expectedCount, $users);

        foreach ($users as $user) {
            self::assertSame($expectedName, $user->name());
            self::assertSame($expectedRole, $user->role());
        }
    }

    public function testMake_makeMultipleWithoutOverriding(): void
    {
        // Arrange
        $expectedCount = 5;

        // Act
        $users = UserFactory::times($expectedCount)->make();

        // Assert
        self::assertCount($expectedCount, $users);
    }

    public function testRaw_makeSingleUser(): void
    {
        // Arrange
        $name = 'john';
        $expectedName = 'john (admin)';
        $expectedRole = UserRole::ADMIN;

        // Act
        $user = UserFactory::new()->admin()->raw()->make(['name' => $name]);

        // Assert
        self::assertSame($expectedName, $user['name']);
        self::assertSame($expectedRole, $user['role']);
    }

    public function testRaw_makeMultiple(): void
    {
        // Arrange
        $name = 'john';
        $expectedName = 'john (admin)';
        $expectedRole = UserRole::ADMIN;
        $expectedCount = 5;

        // Act
        $users = UserFactory::new()->admin()->count($expectedCount)->raw()->make(['name' => $name]);

        // Assert
        self::assertCount($expectedCount, $users);

        foreach ($users as $user) {
            self::assertSame($expectedName, $user['name']);
            self::assertSame($expectedRole, $user['role']);
        }
    }

    public function testRaw_makeMultipleWithoutOverriding(): void
    {
        // Arrange
        $expectedCount = 5;

        // Act
        $users = UserFactory::new()->count($expectedCount)->raw()->make();

        // Assert
        self::assertCount($expectedCount, $users);
    }

    public function testState_givingStateAsArray_expectsStateApplied(): void
    {
        // Arrange
        $expectedEmail = 'john@mail.com';

        // Act
        $user = UserFactory::new()->state(['email' => $expectedEmail])->make();

        // Assert
        self::assertSame($expectedEmail, $user->email);
    }

    public function testState_givingStateAsCallable_expectsStateApplied(): void
    {
        // Arrange
        $expectedEmail = 'john@mail.com';

        // Act
        $user = UserFactory::new()->state(fn () => ['email' => $expectedEmail])->make();

        // Assert
        self::assertSame($expectedEmail, $user->email);
    }

    public function testMake_givingFactoryWithSubFactory_expectsSubFactoryItemCreated(): void
    {
        // Act
        $user = UserFactory::new()->make();

        // Assert
        self::assertInstanceOf(UserProfile::class, $user->profile);
    }

    public function testRaw_givingFactoryWithSubFactory_expectsSubFactoryWillReturnState(): void
    {
        // Act
        $user = UserFactory::new()->raw()->make();

        // Assert
        self::assertIsArray($user['profile']);
        self::assertSame(['bio'], array_keys($user['profile']));
    }
}
