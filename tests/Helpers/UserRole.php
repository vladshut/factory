<?php

declare(strict_types=1);

namespace Tests\Helpers;

enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';
}
