<?php

namespace App\Tests\Application\Service;

use App\Application\DTO\MovieResponseDTO;
use App\Application\Service\CacheServiceInterface;
use App\Application\Service\MovieService;
use App\Domain\Entity\Movie;
use App\Domain\Repository\MovieRepositoryInterface;
use PHPUnit\Framework\TestCase;

class MovieServiceTest extends TestCase
{
    private MovieRepositoryInterface $movieRepositoryMock;
    private MovieService $movieService;

    private CacheServiceInterface $cacheServiceMock;

    protected function setUp(): void
    {
        $this->movieRepositoryMock = $this->createMock(MovieRepositoryInterface::class);
        $this->cacheServiceMock = $this->createMock(CacheServiceInterface::class);
        $this->movieService = new MovieService($this->movieRepositoryMock, $this->cacheServiceMock);
    }

    public function testFetchTopRatedMovieReturnsNullWhenNoMovie(): void
    {
        $this->movieRepositoryMock
            ->method('fetchTopRatedMovie')
            ->willReturn(null);

        $result = $this->movieService->fetchTopRatedMovie();

        $this->assertNull($result);
    }

    public function testFetchTopRatedMovieReturnsMovieDto(): void
    {
        // Mock movie entity
        $mockMovie = $this->createMock(Movie::class);
        $mockMovie->method('getId')->willReturn(1);
        $mockMovie->method('getTitle')->willReturn('Inception');
        $mockMovie->method('getReleaseDate')->willReturn('2010-07-16');
        $mockMovie->method('getVotes')->willReturn(10000);
        $mockMovie->method('getOverview')->willReturn('A thief who steals corporate secrets...');
        $mockMovie->method('getAverageRating')->willReturn(8.8);
        $mockMovie->method('getPosterKey')->willReturn('some_poster_key');
        $mockMovie->method('getVideos')->willReturn([
            ['type' => 'Trailer', 'key' => 'trailer_key', 'name' => 'Inception Trailer']
        ]);
        $mockMovie->method('getProductionCompanies')->willReturn([['name' => 'Warner Bros.']]);

        $this->movieRepositoryMock
            ->method('fetchTopRatedMovie')
            ->willReturn($mockMovie);

        $result = $this->movieService->fetchTopRatedMovie();

        $expectedResult = MovieResponseDTO::toResponseDto(
            1,
            'Inception',
            '2010',
            'Warner Bros.',
            10000,
            'A thief who steals corporate secrets...',
            'https://www.youtube.com/watch?v=trailer_key',
            'Inception Trailer',
            4.4,
            'https://image.tmdb.org/t/p/original/some_poster_key'
        );

        $this->assertSame($expectedResult->toArray(), $result->toArray());
    }

    public function testFetchMoviesByGenreReturnsArrayOfMovieDtos(): void
    {
        // Create mock movies
        $mockMovie1 = $this->createMock(Movie::class);
        $mockMovie1->method('getId')->willReturn(1);
        $mockMovie1->method('getTitle')->willReturn('Movie 1');
        $mockMovie1->method('getReleaseDate')->willReturn('2021-01-01');
        $mockMovie1->method('getVotes')->willReturn(500);
        $mockMovie1->method('getOverview')->willReturn('Overview 1');
        $mockMovie1->method('getAverageRating')->willReturn(7.0);
        $mockMovie1->method('getPosterKey')->willReturn('poster1_key');
        $mockMovie1->method('getVideos')->willReturn([]);
        $mockMovie1->method('getProductionCompanies')->willReturn([]);

        $mockMovie2 = $this->createMock(Movie::class);
        $mockMovie2->method('getId')->willReturn(2);
        $mockMovie2->method('getTitle')->willReturn('Movie 2');
        $mockMovie2->method('getReleaseDate')->willReturn('2021-01-02');
        $mockMovie2->method('getVotes')->willReturn(600);
        $mockMovie2->method('getOverview')->willReturn('Overview 2');
        $mockMovie2->method('getAverageRating')->willReturn(8.0);
        $mockMovie2->method('getPosterKey')->willReturn('poster2_key');
        $mockMovie2->method('getVideos')->willReturn([]);
        $mockMovie2->method('getProductionCompanies')->willReturn([]);

        $this->movieRepositoryMock
            ->method('fetchMoviesByGenre')
            ->willReturn([$mockMovie1, $mockMovie2]);

        $result = $this->movieService->fetchMoviesByGenre(1);

        $expectedResult = [
            MovieResponseDTO::toResponseDto(
                1,
                'Movie 1',
                '2021',
                '',
                500,
                'Overview 1',
                '',
                '',
                3.5,
                'https://image.tmdb.org/t/p/original/poster1_key'
            )->toArray(),
            MovieResponseDTO::toResponseDto(
                2,
                'Movie 2',
                '2021',
                '',
                600,
                'Overview 2',
                '',
                '',
                4.0,
                'https://image.tmdb.org/t/p/original/poster2_key'
            )->toArray(),
        ];

        $this->assertSame($expectedResult, $result);
    }

    public function testFetchMoviesByTitleReturnsArrayOfMovieDtos(): void
    {
        // Create mock movies
        $mockMovie1 = $this->createMock(Movie::class);
        $mockMovie1->method('getId')->willReturn(1);
        $mockMovie1->method('getTitle')->willReturn('Movie Title 1');
        $mockMovie1->method('getReleaseDate')->willReturn('2021-01-01');
        $mockMovie1->method('getVotes')->willReturn(300);
        $mockMovie1->method('getOverview')->willReturn('Overview of movie 1');
        $mockMovie1->method('getAverageRating')->willReturn(6.0);
        $mockMovie1->method('getPosterKey')->willReturn('poster1_key');
        $mockMovie1->method('getVideos')->willReturn([]);
        $mockMovie1->method('getProductionCompanies')->willReturn([]);

        $this->movieRepositoryMock
            ->method('fetchMoviesByTitle')
            ->willReturn([$mockMovie1]);

        $result = $this->movieService->fetchMoviesByTitle('Movie Title');

        $expectedResult = [
            MovieResponseDTO::toResponseDto(
                1,
                'Movie Title 1',
                '2021',
                '',
                300,
                'Overview of movie 1',
                '',
                '',
                3.0,
                'https://image.tmdb.org/t/p/original/poster1_key'
            )->toArray(),
        ];

        $this->assertSame($expectedResult, $result);
    }

    public function testRateMovieReturnsTrue(): void
    {
        $movieId = 1;
        $rate = 5;

        $this->movieRepositoryMock
            ->method('rateMovie')
            ->with($movieId, $rate)
            ->willReturn(true);

        $result = $this->movieService->rateMovie($movieId, $rate);

        $this->assertTrue($result);
    }
}
