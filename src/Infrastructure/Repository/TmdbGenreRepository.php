<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Genre;
use App\Domain\Repository\GenreRepositoryInterface;
use App\Infrastructure\httpClient\Client;

class TmdbGenreRepository implements GenreRepositoryInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly string $apiKey,
        private readonly string $apiUrl
    ) {
    }

    public function fetchGenreList(): array
    {
        $genres = $this->client->get(
            $this->apiUrl . 'genre/movie/list',
            ['api_key' => $this->apiKey]
        );

        return array_map(
            fn($genre) => new Genre($genre['id'], $genre['name']),
            $genres->toArray()['genres']
        );
    }
}
