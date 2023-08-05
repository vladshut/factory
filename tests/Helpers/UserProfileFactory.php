<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Vshut\Factory\Factory;

/**
 * @extends Factory<UserProfile>
 */
final class UserProfileFactory extends Factory
{
    public function makeItem(array $state): UserProfile
    {
        return new UserProfile(...$state);
    }

    public function defaultState(): array
    {
        return [
            'bio' => $this->faker->text(),
        ];
    }
}
