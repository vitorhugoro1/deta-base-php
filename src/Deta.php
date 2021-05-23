<?php

namespace VitorHugoRo\Deta;

use VitorHugoRo\Deta\Exceptions\NeedBaseException;
use VitorHugoRo\Deta\Responses\QueryResponse;
use VitorHugoRo\Deta\Item;

class Deta
{
    private string $baseUrl;

    private string $apiVersion;

    private string $projectKey;

    private string $projectId;

    private ?string $baseName = null;

    private DetaRequest $client;

    public function __construct(
        string $projectId,
        string $projectKey,
        ?string $baseName = null,
        string $baseUrl = 'https://database.deta.sh',
        string $apiVersion = 'v1'
    ) {
        $this->projectId = $projectId;
        $this->projectKey = $projectKey;
        $this->baseName = $baseName;
        $this->baseUrl = $baseUrl;
        $this->apiVersion = $apiVersion;

        $this->client = new DetaRequest(
            "{$this->baseUrl}/{$this->apiVersion}/{$this->projectId}/",
            $this->projectKey
        );
    }

    public function setBaseName(string $baseName): self
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
