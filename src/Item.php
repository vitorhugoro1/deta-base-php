<?php

namespace VitorHugoRo\Deta;

use VitorHugoRo\Deta\Exceptions\RequiredItemFieldException;

class Item
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
