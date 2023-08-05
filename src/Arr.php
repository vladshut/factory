<?php

declare(strict_types=1);

namespace Vshut\Factory;

/**
 * Provides a set of helper methods for working with arrays.
 *
 * @internal
 *
 * @codeCoverageIgnore
 */
class Arr
{
    /**
     * Set an array item to a given value using "dot" notation.
     * If no key is given to the method, the entire array will be replaced.
     */
    public static function set(array &$array, ?string $key, mixed $value): array
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);
        $keysCount = count($keys);

        while ($keysCount > 1) {
            $key = array_shift($keys);
            --$keysCount;

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}
