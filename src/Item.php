<?php

namespace VitorHugoRo\Deta;

use VitorHugoRo\Deta\Exceptions\RequiredItemFieldException;

class Item
{
    private $key;

    private $body;

    public function __construct(string $key, array $body)
    {
        $this->key = $key;
        $this->body = $body;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getBody()
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
