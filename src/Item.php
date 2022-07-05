<?php

namespace VitorHugoRo\Deta;

use ArrayAccess;
use VitorHugoRo\Deta\Exceptions\RequiredItemFieldException;

class Item implements ArrayAccess
{
    public function __construct(
        private string $key,
        private array $body
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->body[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->body[$offset]) ? $this->body[$offset] : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->body[] = $value;
        } else {
            $this->body[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->body[$offset]);
    }

    public static function fromResponse(array $params): self
    {
        if (!array_key_exists('key', $params)) {
            RequiredItemFieldException::missing('key');
        }

        return new static(
            $params['key'],
            array_filter($params, fn ($key) => $key !== 'key', ARRAY_FILTER_USE_KEY)
        );
    }
}
