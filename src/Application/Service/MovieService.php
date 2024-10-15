<?php

namespace App\Application\Service;

use App\Application\DTO\MovieResponseDTO;
use App\Domain\Entity\Movie;
use App\Domain\Repository\MovieRepositoryInterface;

class MovieService implements MovieInterface
{
    private const VIDEO_URI = 'https://www.youtube.com/watch?v=';
    private const IMAGE_URI = 'https://image.tmdb.org/t/p/original/';

    private const MOVIE_CACHE_KEY_PREFIX = 'movies';

    public function __construct(
        private readonly MovieRepositoryInterface $movieRepository,
        private readonly CacheServiceInterface $cacheService
    ) {
    }

    public function fetchTopRatedMovie(): ?MovieResponseDTO
    {
        $cacheKey = self::MOVIE_CACHE_KEY_PREFIX . 'top_rated';
        if ($cachedData = $this->cacheService->get($cacheKey)) {
            $cachedMovie = json_decode($cachedData, true);
            return MovieResponseDTO::toResponseDto(
                $cachedMovie['id'],
                $cachedMovie['title'],
                $cachedMovie['releaseYear'],
                $cachedMovie['productionCompanies'],
                $cachedMovie['votes'],
                $cachedMovie['description'],
                $cachedMovie['videoPath'],
                $cachedMovie['videoTitle'],
                $cachedMovie['stars'],
                $cachedMovie['posterPath'],
            );
        }

        if (is_null($movie = $this->movieRepository->fetchTopRatedMovie())) {
            return null;
        }

        $movieDto = $this->buildResponseDto($movie);
        $this->cacheService->set($cacheKey, json_encode($movieDto->toArray()));

        return $movieDto;
    }

    public function fetchMoviesByGenre(int $genreId): array
    {
        $cacheKey = self::MOVIE_CACHE_KEY_PREFIX . '-genre-' . $genreId;
        if ($cachedData = $this->cacheService->get($cacheKey)) {
            return json_decode($cachedData, true);
        }

        if (empty($listMoviesByGenre = $this->movieRepository->fetchMoviesByGenre($genreId))) {
            return [];
        }
        $listMoviesByGenre = array_map(
            fn($movie) => $this->buildResponseDto($movie)->toArray(),
            $listMoviesByGenre
        );

        $this->cacheService->set($cacheKey, json_encode($listMoviesByGenre));

        return $listMoviesByGenre;
    }

    public function fetchMoviesByTitle(string $title): array
    {
        $cacheKey = self::MOVIE_CACHE_KEY_PREFIX . '-keyword-' . $title;
        if ($cachedData = $this->cacheService->get($cacheKey)) {
                return json_decode($cachedData, true);
        }

        if (empty($listMoviesByTitle = $this->movieRepository->fetchMoviesByTitle($title))) {
            return [];
        }
        $listMoviesByTitle = array_map(
            fn($movie) => $this->buildResponseDto($movie)->toArray(),
            $listMoviesByTitle
        );
        $this->cacheService->set($cacheKey, json_encode($listMoviesByTitle));

        return $listMoviesByTitle;
    }

    public function rateMovie(int $movieId, int $rate): bool
    {
        return $this->movieRepository->rateMovie($movieId, $rate);
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
            $videoPath = self::VIDEO_URI . $video['key'];
            $videoTitle = $video['name'];
        }


        $poster = self::IMAGE_URI . $movie->getPosterKey();

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
}
