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

    /**
     * Set a Deta Base
     *
     * @param string $baseName
     *
     * @return Deta
     */
    public function setBaseName(string $baseName): Deta
    {
        $this->baseName = $baseName;

        return $this;
    }

    /**
     * Query or Fetch all data from selected Deta Base
     *
     * @return \VitorHugoRo\Deta\Responses\QueryResponse
     */
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

    /**
     * Insert a new item on selected Deta Base,
     * if not provide an key then this will be automatic generated.
     *
     * @param array $params
     *
     * @return \VitorHugoRo\Deta\Item
     */
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
     * Update a Item from a selected Deta Base
     * Can update with many ways who you can check on Deta Documentation.
     *
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

    /**
     * Get a Item from selected Deta Base
     *
     * @param string $key
     *
     * @return \VitorHugoRo\Deta\Item
     */
    public function get(string $key): Item
    {
        if (!$this->baseName) {
            NeedBaseException::notHasBase();
        }

        $response = $this->client->request("GET", "{$this->baseName}/items/{$key}");

        return Item::fromResponse($response);
    }

    /**
     * Delete a Item from a selected Deta Base then return if is sucessfull
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        if (!$this->baseName) {
            NeedBaseException::notHasBase();
        }

        $response = $this->client->request("DELETE", "{$this->baseName}/items/{$key}");

        return $response['key'] === $key;
    }
}
