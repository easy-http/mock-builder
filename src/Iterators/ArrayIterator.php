<?php

namespace EasyHttp\MockBuilder\Iterators;

class ArrayIterator implements \Iterator
{
    private array $collection = [];

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function next(): void
    {
        next($this->collection);
    }

    public function key(): ?string
    {
        return key($this->collection);
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->collection);
    }

    public function rewind(): void
    {
        reset($this->collection);
    }

    public function current()
    {
        return current($this->collection);
    }
}
