<?php

namespace VitorHugoRo\Deta\Responses;

use Countable;
use Iterator;
use VitorHugoRo\Deta\Item;

class QueryResponse implements Iterator, Countable
{
    private $position = 0;

    public function __construct(
        private array $items,
        private int $size,
        private ?string $lastKey = null
    ) {
    }

    public function getLastKey(): ?string
    {
        return $this->lastKey;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function first(): ?Item
    {
        return reset($this->items);
    }

    public function current(): Item
    {
        return $this->items[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function count(): int
    {
        return $this->size;
    }
}
