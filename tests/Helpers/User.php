<?php

declare(strict_types=1);

namespace Tests\Helpers;

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
