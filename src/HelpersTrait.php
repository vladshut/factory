<?php

declare(strict_types=1);

namespace Vshut\Factory;

trait HelpersTrait
{
    /**
     * @template TValue
     * @template TDefault
     *
     * @param TValue   $value
     * @param TDefault $default
     *
     * @return TDefault|TValue
     */
    protected function optional(mixed $value, float $weight = 0.5, mixed $default = null): mixed
    {
        return $this->faker->optional($weight, $default)->passthrough($value);
    }

    /**
     * Selects random enum value.
     *
     * @template TEnum
     *
     * @param class-string<TEnum> $enumClass
     *
     * @return TEnum
     */
    protected function randomEnum(string $enumClass): mixed
    {
        return $this->faker->randomElement($enumClass::cases());
    }

    /**
     * Creates array of specific size with values returned by callable src.
     */
    protected function collect(int $length, callable $src): array
    {
        return array_map(
            fn (callable $srcItem) => $srcItem(),
            array_fill(0, $length, $src),
        );
    }
}
