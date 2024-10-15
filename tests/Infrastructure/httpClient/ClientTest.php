<?php

namespace App\Tests\Infrastructure\httpClient;

use App\Infrastructure\httpClient\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ClientTest extends TestCase
{
    private HttpClientInterface $httpClientMock;
    private Client $client;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->client = new Client($this->httpClientMock);
    }

    public function testGetMethodCallsHttpClientWithCorrectParameters(): void
    {
        $endpoint = 'https://api.example.com/resource';
        $query = ['param' => 'value'];

        // Create a mock response
        $responseMock = $this->createMock(ResponseInterface::class);
        $this->httpClientMock
            ->method('request')
            ->with('GET', $endpoint, ['query' => $query])
            ->willReturn($responseMock);

        $response = $this->client->get($endpoint, $query);

        $this->assertSame($responseMock, $response);
    }

    public function testPostMethodCallsHttpClientWithCorrectParameters(): void
    {
        $endpoint = 'https://api.example.com/resource';
        $query = ['param' => 'value'];
        $body = ['data' => 'some data'];

        // Create a mock response
        $responseMock = $this->createMock(ResponseInterface::class);
        $this->httpClientMock
            ->method('request')
            ->with('POST', $endpoint, ['query' => $query, 'body' => $body])
            ->willReturn($responseMock);

        $response = $this->client->post($endpoint, $query, $body);

        $this->assertSame($responseMock, $response);
    }
}
