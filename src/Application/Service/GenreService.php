<?php

namespace App\Application\Service;

use App\Application\DTO\GenreResponseDTO;
use App\Domain\Repository\GenreRepositoryInterface;

class GenreService implements GenreInterface
{
    public function __construct(
        private readonly GenreRepositoryInterface $genreRepository,
        private readonly CacheServiceInterface $cacheService
    ) {
    }

    public function fetchGenresList(): array
    {
        $cacheKey = 'genres-list';
        if ($cachedData = $this->cacheService->get($cacheKey)) {
            return json_decode($cachedData, true);
        };

        if (empty($genreList = $this->genreRepository->fetchGenreList())) {
            return [];
        }
        $genreListDto = $this->buildResponseDto($genreList);
        $this->cacheService->set($cacheKey, json_encode($genreListDto));

        return $genreListDto;
    }

    private function buildResponseDto(array $genreList): array
    {
        return array_map(
            fn($genre) => (new GenreResponseDTO($genre->getId(), $genre->getName()))->toArray(),
            $genreList
        );
    }
}
