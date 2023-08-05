<?php

declare(strict_types=1);

namespace Tests\Helpers;

final readonly class UserProfile
{
    public function __construct(
        public readonly string $bio,
    ) {
    }
}
