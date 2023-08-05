<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Vshut\Factory\RawFactory;

final class UserArrayFactory extends RawFactory
{
    public function defaultState(): array
    {
        return [
            'username' => $this->faker->userName(),
            'email' => $this->faker->email,
            'name' => $this->faker->name(),
            'country' => $this->optional($this->faker->countryCode()),
            'role' => $this->randomEnum(UserRole::class),
            'tags' => $this->collect(3, fn () => $this->faker->word()),
        ];
    }
}
