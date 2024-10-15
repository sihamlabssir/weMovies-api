<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Movie;

interface MovieRepositoryInterface
{
    public function fetchTopRatedMovie(): ?Movie;

    /**
     * @return array<Movie>
     */
    public function fetchMoviesByGenre(int $genreId): array;

    /**
     * @return array<Movie>
     */
    public function fetchMoviesByTitle(string $title): array;

    public function rateMovie(int $movieId, int $rate): bool;
}
