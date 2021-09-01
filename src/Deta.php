<?php

namespace VitorHugoRo\Deta;

use VitorHugoRo\Deta\Exceptions\NeedBaseException;
use VitorHugoRo\Deta\Responses\QueryResponse;
use VitorHugoRo\Deta\Item;

class Deta
{
    public function __construct(
        private string $projectId,
        private string $projectKey,
        private ?string $baseName = null,
        private ?DetaRequest $client = null
    ) {
        $this->client = $this->client ?? new DetaRequest(
            $this->projectId,
            $this->projectKey
        );
    }

    public function setBaseName(string $baseName): Deta
    {
        $this->baseName = $baseName;

        return $this;
    }

    public function fetch(): QueryResponse
    {
        if (!$this->baseName) {
            NeedBaseException::notHasBase();
        }

        $response = $this->client->request("POST", "{$this->baseName}/query");

        return new QueryResponse(
            array_map(fn ($item) => Item::fromResponse($item), $response['items']),
            $response['paging']['size'],
            $response['paging']['last'] ?? null
        );
    }

    public function insert(array $params): Item
    {
        if (!$this->baseName) {
            NeedBaseException::notHasBase();
        }

        $insertResponse = $this->client->request("POST", "{$this->baseName}/items", [
            'json' => [
                'item' => $params
            ]
        ]);

        return Item::fromResponse($insertResponse);
    }

    /**
     * @param string $key
     * @param array $set
     * @param array $increment
     * @param array $append
     * @param array|string $delete
     *
     * @return Item
     */
    public function update(
        string $key,
        array $set = null,
        array $increment = null,
        array $append = null,
        array $prepend = null,
        $delete = null
    ): Item {
        if (!$this->baseName) {
            NeedBaseException::notHasBase();
        }

        $updatedResponse = $this->client->request("PATCH", "{$this->baseName}/items/{$key}", [
            'json' => array_filter(compact('set', 'increment', 'append', 'prepend', 'delete'))
        ]);

        return $this->get($key);
    }

    public function get(string $key): Item
    {
        if (!$this->baseName) {
            NeedBaseException::notHasBase();
        }

        $response = $this->client->request("GET", "{$this->baseName}/items/{$key}");

        return Item::fromResponse($response);
    }

    public function delete(string $key): bool
    {
        if (!$this->baseName) {
            NeedBaseException::notHasBase();
        }

        $response = $this->client->request("DELETE", "{$this->baseName}/items/{$key}");

        return $response['key'] === $key;
    }
}
