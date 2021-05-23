<?php

namespace VitorHugoRo\Deta\Responses;

use VitorHugoRo\Deta\Item;

class QueryResponse
{
    /**
     * @var array<Item>
     */
    private array $items;

    private int $size;

    private ?string $lastKey;

    public function __construct(
        array $items,
        int $size,
        ?string $lastKey = null
    ) {
        $this->items = $items;
        $this->size = $size;
        $this->lastKey = $lastKey;
    }

    public function getLastKey()
    {
        return $this->lastKey;
    }

    public function getSize()
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
