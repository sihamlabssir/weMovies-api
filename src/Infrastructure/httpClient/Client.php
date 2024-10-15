<?php

namespace App\Infrastructure\httpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Client
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    public function get(string $endpoint, $query = []): ResponseInterface
    {
        return $this->client->request(
            'GET',
            $endpoint,
            [
                'query' => $query
            ]
        );
    }

    public function post(string $endpoint, $query = [], $body = []): ResponseInterface
    {
        return $this->client->request(
            'POST',
            $endpoint,
            [
                'query' => $query,
                'body' => $body
            ]
        );
    }
}
