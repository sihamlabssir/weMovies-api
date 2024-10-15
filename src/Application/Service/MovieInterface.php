<?php

namespace App\Application\Service;

use App\Application\DTO\MovieResponseDTO;

interface MovieInterface
{
    public function fetchTopRatedMovie(): ?MovieResponseDTO;

    public function fetchMoviesByGenre(int $genreId): array;

    public function fetchMoviesByTitle(string $title): array;

    public function rateMovie(int $movieId, int $rate): bool;
}
