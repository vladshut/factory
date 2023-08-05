<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Vshut\Factory\Factory;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    public function defaultState(): array
    {
        return [
            'username' => $this->faker->userName(),
            'email' => $this->faker->email,
            'name' => $this->faker->name(),
            'profile' => UserProfileFactory::new(),
            'role' => $this->randomEnum(UserRole::class),
            'tags' => $this->collect(3, fn () => $this->faker->word),
            'country' => $this->optional($this->faker->countryCode()),
        ];
    }

    public function admin(): self
    {
        return $this->state(fn () => ['role' => UserRole::ADMIN]);
    }

    public function withEmail(string $email): self
    {
        return $this->state(['email' => $email]);
    }

    public function makeItem(array $state): User
    {
        $user = new User(
            $state['username'],
            $state['email'],
            $state['country'],
            $state['profile'],
        );

        $user->setName($state['name']);
        $user->setRole($state['role']);

        foreach ($state['tags'] as $tag) {
            $user->addTag($tag);
        }

        return $user;
    }

    protected function fixFinalState(array $state): array
    {
        if ($state['role'] === UserRole::ADMIN) {
            $state['name'] .= ' (admin)';
        }

        return $state;
    }
}
