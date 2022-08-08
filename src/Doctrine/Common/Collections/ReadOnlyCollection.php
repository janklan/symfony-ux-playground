<?php

namespace App\Doctrine\Common\Collections;

use Closure;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

/**
 * Read-only collection wrapper.
 *
 * Prohibits any write/modify operations, but allows all non-modifying.
 *
 * The overridden methods are final to avoid overriding the read-only protection in any extending classes that may come.
 *
 * @see https://github.com/Kdyby/DoctrineCollectionsReadonly I got inspired by this library, but brought the code up-to-speed with 2022+
 *
 * @psalm-template TKey of array-key
 * @psalm-template T
 * @template-implements Collection<TKey,T>
 * @template-implements Selectable<TKey,T>
 * @psalm-consistent-constructor
 */
class ReadOnlyCollection implements Collection, Selectable
{
    public function __construct(private readonly Collection $collection)
    {
        // Void
    }

    /**
     * {@inheritdoc}
     */
    final public function add($element): bool
    {
        throw new ReadOnlyCollectionException('add an element to');
    }

    /**
     * {@inheritdoc}
     */
    final public function clear(): void
    {
        throw new ReadOnlyCollectionException('clear');
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element): bool
    {
        return $this->collection->contains($element);
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key): bool
    {
        return $this->collection->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function current(): mixed
    {
        return $this->collection->current();
    }

    /**
     * {@inheritdoc}
     */
    public function exists(Closure $p): bool
    {
        return $this->collection->exists($p);
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $p): Collection
    {
        return $this->collection->filter($p);
    }

    /**
     * {@inheritdoc}
     */
    public function first(): mixed
    {
        return $this->collection->first();
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(Closure $p): bool
    {
        return $this->collection->forAll($p);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key): mixed
    {
        return $this->collection->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys(): array
    {
        return $this->collection->getKeys();
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): array
    {
        return $this->collection->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function indexOf($element): int|string|bool
    {
        return $this->collection->indexOf($element);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return $this->collection->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int|string|null
    {
        return $this->collection->key();
    }

    /**
     * {@inheritdoc}
     */
    public function last(): mixed
    {
        return $this->collection->last();
    }

    /**
     * {@inheritdoc}
     */
    public function map(Closure $func): Collection
    {
        return $this->collection->map($func);
    }

    /**
     * {@inheritdoc}
     */
    public function matching(Criteria $criteria): Collection
    {
        if (!$this->collection instanceof Selectable) {
            throw ReadOnlyCollectionException::notSelectable($this->collection);
        }

        return $this->collection->matching($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): mixed
    {
        return $this->collection->next();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->collection->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        return $this->collection->offsetGet($offset);
    }

    /**
     * {@inheritdoc}
     */
    final public function offsetSet($offset, $value): void
    {
        throw new ReadOnlyCollectionException('set an element in');
    }

    /**
     * {@inheritdoc}
     */
    final public function offsetUnset($offset): void
    {
        throw new ReadOnlyCollectionException('remove an element from');
    }

    /**
     * {@inheritdoc}
     */
    public function partition(Closure $p): array
    {
        return $this->collection->partition($p);
    }

    /**
     * {@inheritdoc}
     */
    final public function remove($key): mixed
    {
        throw new ReadOnlyCollectionException('remove an element from');
    }

    /**
     * {@inheritdoc}
     */
    final public function removeElement($element): bool
    {
        throw new ReadOnlyCollectionException('remove an element from');
    }

    /**
     * {@inheritdoc}
     */
    final public function set($key, $value): void
    {
        throw new ReadOnlyCollectionException('set an element in');
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null): array
    {
        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->collection->toArray();
    }
}
