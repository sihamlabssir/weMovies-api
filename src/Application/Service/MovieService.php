<?php

namespace App\Application\Service;

use App\Application\DTO\MovieResponseDTO;
use App\Domain\Entity\Movie;
use App\Domain\Repository\MovieRepositoryInterface;

class MovieService implements MovieInterface
{
    private const YOUTUBE_URI = 'https://www.youtube.com/watch?v=';
    private const TMDB_IMAGE_URI = 'https://image.tmdb.org/t/p/original/';

    public function __construct(private readonly MovieRepositoryInterface $movieRepository)
    {
    }

    public function fetchTopRatedMovie(): ?MovieResponseDTO
    {
        $movie = $this->movieRepository->fetchTopRatedMovie();
        return !is_null($movie) ? $this->buildResponseDto($movie) : null;
    }

    public function fetchMoviesByGenre(int $genreId): array
    {
        $listMoviesByGenre = $this->movieRepository->fetchMoviesByGenre($genreId);
        return array_map(
            fn($movie) => $this->buildResponseDto($movie)->toArray(),
            $listMoviesByGenre
        );
    }

    private function buildResponseDto(Movie $movie): MovieResponseDTO
    {
        $dateTime = new \DateTime($movie->getReleaseDate());
        $year = $dateTime->format('Y');

        foreach ($movie->getProductionCompanies() as $company) {
            $productionCompanies[] = $company['name'];
        }

        foreach ($movie->getVideos() as $video) {
            if ('Trailer' !== $video['type']) {
                continue;
            }
            $videoPath = self::YOUTUBE_URI . $video['key'];
            $videoTitle = $video['name'];
        }


        $poster = self::TMDB_IMAGE_URI . $movie->getPosterKey();

        return MovieResponseDTO::toResponseDto(
            $movie->getId(),
            $movie->getTitle(),
            $year,
            implode(', ', $productionCompanies ?? []),
            $movie->getVotes(),
            $movie->getOverview(),
            $videoPath ?? '',
            $videoTitle ?? '',
            $movie->getAverageRating() / 2,
            $poster
        );
    }

    public function fetchMoviesByTitle(string $title): array
    {
        $listMoviesByTitle = $this->movieRepository->fetchMoviesByTitle($title);
        return array_map(
            fn($movie) => $this->buildResponseDto($movie)->toArray(),
            $listMoviesByTitle
        );
    }

    public function rateMovie(int $movieId, int $rate): bool
    {
        return $this->movieRepository->rateMovie($movieId, $rate);
    }
}
