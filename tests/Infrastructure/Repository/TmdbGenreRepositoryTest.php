<?php

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Entity\Genre;
use App\Infrastructure\httpClient\Client;
use App\Infrastructure\Repository\TmdbGenreRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TmdbGenreRepositoryTest extends TestCase
{
    private Client $clientMock;
    private TmdbGenreRepository $repository;
    private string $apiKey = 'fake_api_key';
    private string $apiUrl = 'https://api.themoviedb.org/3/';

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->repository = new TmdbGenreRepository($this->clientMock, $this->apiKey, $this->apiUrl);
    }

    public function testFetchGenreListReturnsArrayOfGenres(): void
    {
        // Create a mock ResponseInterface
        $responseMock = $this->createMock(ResponseInterface::class);

        // Set up the response to return the genre list
        $responseMock->method('toArray')->willReturn([
            'genres' => [
                ['id' => 1, 'name' => 'Action'],
                ['id' => 2, 'name' => 'Drama'],
            ],
        ]);

        // Mock the client to return the response mock
        $this->clientMock->method('get')->willReturn($responseMock);

        $genres = $this->repository->fetchGenreList();

        // Assertions
        $this->assertCount(2, $genres);
        $this->assertInstanceOf(Genre::class, $genres[0]);
        $this->assertEquals(1, $genres[0]->getId());
        $this->assertEquals('Action', $genres[0]->getName());
    }
}
