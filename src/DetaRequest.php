<?php

namespace VitorHugoRo\Deta;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use VitorHugoRo\Deta\Exceptions\RequestException;

class DetaRequest
{
    private Client $client;

    public function __construct(
        private string $projectId,
        private string $projectKey,
        private string $baseUri = 'https://database.deta.sh/v1'
    ) {
        $this->client = new Client([
            'base_uri' => "{$this->baseUri}/{$this->projectId}/",
            'headers' => [
                'X-API-Key' => $this->projectKey,
                'Content-Type' => 'application/json'
            ],
            'http_errors' => false
        ]);
    }

    public function request(string $method, string $uri, array $options = []): array
    {
        $response = $this->client->request($method, $uri, $options);

        $this->checkErrors($response);

        return json_decode($response->getBody(), true);
    }

    private function checkErrors(ResponseInterface $response): void
    {
        if (in_array($response->getStatusCode(), [200, 201], true)) {
            return;
        }

        throw new RequestException(
            (string) $response->getBody(),
            $response->getStatusCode()
        );
    }
}
