<?php

namespace VitorHugoRo\Deta\Responses;

use VitorHugoRo\Deta\Item;

class QueryResponse
{
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
}
