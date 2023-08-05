<?php

declare(strict_types=1);

namespace Vshut\Factory;

use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;

/**
 * @template T
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class Factory
{
    use HelpersTrait;

    /**
     * Instance of Faker generator.
     */
    protected Faker $faker;

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param int|null   $count  - number of items to create
     * @param callable[] $states - array of callables that return array of properties to override
     * @param bool       $isRaw  - whether to return final state or create item
     */
    final public function __construct(
        protected ?int $count = null,
        protected array $states = [],
        protected bool $isRaw = false,
    ) {
        $this->faker = FakerFactory::create();
    }

    /**
     * Create and returns new item based on provided data.
     *
     * @return T
     */
    abstract public function makeItem(array $state);

    /**
     * Provide default state of the items.
     */
    abstract public function defaultState(): array;

    /**
     * Get a new factory instance for the given state.
     */
    public static function new(array|callable $state = []): static
    {
        return (new static())->state($state);
    }

    /**
     * Get a new factory instance for the given number of items.
     */
    public static function times(?int $count): static
    {
        return static::new()->count($count);
    }

    /**
     * Specify how many items should be generated.
     */
    public function count(?int $count): static
    {
        return $this->cloneSelf(count: $count);
    }

    /**
     * Specify whether to return final state or create item.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function raw(bool $isRaw = true): static
    {
        return $this->cloneSelf(isRaw: $isRaw);
    }

    /**
     *  Add a new state transformation.
     */
    public function state(array|callable $state): static
    {
        if (empty($state)) {
            return $this->cloneSelf();
        }

        if (is_array($state)) {
            $state = static function () use ($state) {
                return $state;
            };
        }

        $states = $this->states;
        $states[] = $state;

        return $this->cloneSelf(states: $states);
    }

    /**
     * Make an array of items or a single item if count is not specified (equals to null).
     *
     * @return T|T[]
     */
    public function make(array|callable $state = [])
    {
        return $this->state($state)->_make();
    }

    /**
     *  Make a single item.
     *
     * @return T
     */
    public function makeOne(array|callable $state = [])
    {
        return $this->count(null)->state($state)->_make();
    }

    /**
     * Make an array of items: one item per each state.
     *
     * @return T[]
     */
    public function makeMany(iterable $states): array
    {
        return array_map(
            fn (callable|array $state) => $this->state($state)->_make(),
            [...$states]
        );
    }

    /**
     * Fix final state. It can be overridden in child classes to add some final touches.
     */
    protected function fixFinalState(array $state): array
    {
        return $state;
    }

    /**
     * Create a new instance of the factory builder with the given mutated properties.
     */
    protected function cloneSelf(
        ?int $count = null,
        ?array $states = null,
        ?bool $isRaw = null,
    ): static {
        return new static(
            count: $count ?? $this->count,
            states: $states ?? $this->states,
            isRaw: $isRaw ?? $this->isRaw,
        );
    }

    /**
     * Reduce all states to a single final state.
     */
    protected function finalState(): array
    {
        $finalState = $this->defaultState();

        foreach ($this->states as $callable) {
            foreach ($callable($finalState) as $key => $value) {
                Arr::set($finalState, $key, $value);
            }
        }

        $finalState = $this->triggerFactoriesInState($finalState);

        return $this->fixFinalState($finalState);
    }

    /**
     * Make a single item or an array of items.
     *
     * @return T|T[]
     */
    private function _make(): mixed
    {
        $single = $this->isRaw ? $this->finalState(...) : fn () => $this->makeItem($this->finalState());

        $collection = $this->collect($this->count ?? 1, $single);

        return $this->count === null ? reset($collection) : $collection;
    }

    /**
     * Find factories in the state, create items and replace them with created items.
     */
    private function triggerFactoriesInState(array $state): array
    {
        $result = [];

        foreach ($state as $key => $value) {
            if ($value instanceof Factory) {
                $result[$key] = $this->isRaw ? $value->raw()->make() : $value->make();

                continue;
            }

            if (is_array($value)) {
                $result[$key] = $this->triggerFactoriesInState($value);

                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
