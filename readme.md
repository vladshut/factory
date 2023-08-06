# vladshut/factory
---

[![pipeline status](https://gitlab.com/vladshut/factory/badges/master/pipeline.svg)](https://gitlab.com/vladshut/factory/-/commits/master)
[![coverage report](https://gitlab.com/vladshut/factory/badges/master/coverage.svg)](https://gitlab.com/vladshut/factory/-/commits/master)

---
Base classes for creating factories for data (arrays, objects).

Inspired by laravel [factory library](https://laravel.com/docs/10.x/eloquent-factories). 

## Installation
`composer require --dev vladshut/factory`

## Usage examples
```php
<?php
declare(strict_types=1);

namespace Tests\Helpers;

// We have next classes objects of which we want to create using factories 

final class User
{
    private string $name;
    private array $tags = [];

    public function __construct(
        public readonly string $username,
        public readonly string $email,
        public readonly ?string $country,
        public readonly ?UserProfile $profile = null,
        private UserRole $role = UserRole::USER,
    ) {
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setRole(UserRole $role): void
    {
        $this->role = $role;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function addTag(string $tag): void
    {
        $this->tags[] = $tag;
    }

    public function name(): string
    {
        return $this->name;
    }
}

final readonly class UserProfile
{
    public function __construct(
        public readonly string $bio,
    ) {
    }
}

// Let's create factories for these classes

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

// Now these factories can be used to create objects
$user = UserFactory::new()->make();
$userArray = UserFactory::new()->raw()->make();
$userArray = UserArrayFactory::new()->make();

// It is possible to create array of objects
$users = UserFactory::new()->count(10)->make();
$usersArray = UserFactory::new()->count(10)->raw()->make();

// Using states allow to create objects with overridden properties
$admin = UserFactory::new()->state(['role' => 'admin'])->make();

// It is possible to use custom state to override properties
$admin = UserFactory::new()->admin()->make();

// It is possible to create single object using makeOne method
$admin = UserFactory::new()->admin()->makeOne();

// Also it is possible to pass state to the make, makeOne methods
$user = UserFactory::new()->make(['role' => 'admin']);
$user = UserFactory::new()->makeOne(['role' => 'admin']);

// It is possible to create multiple objects with a sequence of states
$states = [['email' => 'foo@mail.com'], ['email' => 'bar@mail.com']];
$users = UserFactory::new()->makeMany($states); // will create 2 users: one with foo@mail, another with bar@mail

```

## Tests

`make test:run`

---

![buy me a coffe](https://img.shields.io/badge/Buy_Me_A_Coffee-FFDD00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)
