<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Movie;
use App\Domain\Repository\MovieRepositoryInterface;
use App\Infrastructure\httpClient\Client;

class TmdbMovieRepository implements MovieRepositoryInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly string $apiKey,
        private readonly string $apiUrl
    ) {
    }

    public function fetchTopRatedMovie(): ?Movie
    {
        $topRatedMovie = $this->client->get($this->apiUrl . 'movie/top_rated', ['api_key' => $this->apiKey]);

        $moviesData = $topRatedMovie->toArray();

        if (empty($moviesData['total_results'])) {
            return null;
        }

        $movieDetails = $this->client->get(
            $this->apiUrl . 'movie/' . $moviesData['results'][0]['id'],
            [
                'api_key' => $this->apiKey,
                'append_to_response' => 'videos'
            ]
        )->toArray();

        return new Movie(
            $movieDetails['id'],
            $movieDetails['title'],
            $movieDetails['release_date'],
            $movieDetails['production_companies'],
            $movieDetails['vote_count'],
            $movieDetails['overview'],
            $movieDetails['videos']['results'],
            $movieDetails['vote_average'],
            $movieDetails['poster_path']
        );
    }

    public function fetchMoviesByGenre(int $genreId): array
    {
        $genreMovies = $this->client->get(
            $this->apiUrl . 'discover/movie',
            [
                'api_key' => $this->apiKey,
                'with_genres' => $genreId
            ]
        );

        $moviesData = $genreMovies->toArray();

        if (empty($moviesData['total_results'])) {
            return [];
        }

        $movies = [];
        foreach ($moviesData['results'] as $movie) {
            $movieDetails = $this->client->get(
                $this->apiUrl . 'movie/' . $movie['id'],
                [
                    'api_key' => $this->apiKey,
                    'append_to_response' => 'videos'
                ]
            )->toArray();

            $movies[] = new Movie(
                $movieDetails['id'],
                $movieDetails['title'],
                $movieDetails['release_date'],
                $movieDetails['production_companies'],
                $movieDetails['vote_count'],
                $movieDetails['overview'],
                $movieDetails['videos']['results'],
                $movieDetails['vote_average'],
                $movieDetails['poster_path']
            );
        }

        return $movies;
    }

    public function fetchMoviesByTitle(string $title): array
    {
        $searchMovies = $this->client->get(
            $this->apiUrl . 'search/movie',
            [
                'api_key' => $this->apiKey,
                'query' => $title
            ]
        );

        $moviesData = $searchMovies->toArray();

        if (empty($moviesData['total_results'])) {
            return [];
        }

        $movies = [];
        foreach ($moviesData['results'] as $movie) {
            $movieDetails = $this->client->get(
                $this->apiUrl . 'movie/' . $movie['id'],
                [
                    'api_key' => $this->apiKey,
                    'append_to_response' => 'videos'
                ]
            )->toArray();

            $movies[] = new Movie(
                $movieDetails['id'],
                $movieDetails['title'],
                $movieDetails['release_date'],
                $movieDetails['production_companies'],
                $movieDetails['vote_count'],
                $movieDetails['overview'],
                $movieDetails['videos']['results'],
                $movieDetails['vote_average'],
                $movieDetails['poster_path'] ?? ''
            );
        }

        return $movies;
    }

    public function rateMovie(int $movieId, int $rate): bool
    {
        try {
            $this->client->post(
                $this->apiUrl . 'movie/' . $movieId . '/rating',
                ['api_key' => $this->apiKey],
                ['value' => $rate]
            );
        } catch (\Exception $e) {
            return true;
        }

        /** TODO : get rid of 401 as TMDB API does not allow, this should work fine when we rate movies internally with our own APIS */
        return true;
    }
}
