<?php

declare(strict_types=1);

namespace Vshut\Factory;

abstract class RawFactory extends Factory
{
    public function makeItem(array $state): array
    {
        return $state;
    }
}
