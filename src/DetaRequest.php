<?php

namespace VitorHugoRo\Deta;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class DetaRequest
{
    private Client $client;

    public function __construct(
        string $baseUri,
        string $projectKey
    ) {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'headers' => [
                'X-API-Key' => $projectKey,
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

    private function checkErrors(ResponseInterface $response)
    {
        if (in_array($response->getStatusCode(), [200, 201], true)) {
            return;
        }

        // Add error traits
    }
}
