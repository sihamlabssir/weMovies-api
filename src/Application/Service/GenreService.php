<?php

namespace App\Application\Service;

use App\Application\DTO\GenreResponseDTO;
use App\Domain\Entity\Genre;
use App\Domain\Repository\GenreRepositoryInterface;

class GenreService implements GenreInterface
{
    public function __construct(private readonly GenreRepositoryInterface $genreRepository)
    {
    }

    public function fetchGenresList(): array
    {
        $genreList = $this->genreRepository->fetchGenreList();
        return !empty($genreList) ? $this->buildResponseDto($genreList) : [];
    }

    private function buildResponseDto(array $genreList): array
    {
        return array_map(
            fn($genre) => (new GenreResponseDTO($genre->getId(), $genre->getName()))->toArray(),
            $genreList
        );
    }
}
