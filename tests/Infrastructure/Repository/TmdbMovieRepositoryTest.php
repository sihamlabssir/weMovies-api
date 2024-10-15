<?php

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Entity\Movie;
use App\Infrastructure\httpClient\Client;
use App\Infrastructure\Repository\TmdbMovieRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TmdbMovieRepositoryTest extends TestCase
{
    private Client $clientMock;
    private TmdbMovieRepository $repository;
    private string $apiKey = 'fake_api_key';
    private string $apiUrl = 'https://api.themoviedb.org/3/';

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->repository = new TmdbMovieRepository($this->clientMock, $this->apiKey, $this->apiUrl);
    }

    public function testFetchTopRatedMovieReturnsMovie(): void
    {
        $responseMockTopRated = $this->createMock(ResponseInterface::class);
        $responseMockDetails = $this->createMock(ResponseInterface::class);

        $responseMockTopRated->method('toArray')->willReturn([
            'total_results' => 1,
            'results' => [['id' => 123]],
        ]);

        $responseMockDetails->method('toArray')->willReturn([
            'id' => 123,
            'title' => 'Test Movie',
            'release_date' => '2024-01-01',
            'production_companies' => [['name' => 'Test Company']],
            'vote_count' => 100,
            'overview' => 'This is a test movie.',
            'videos' => ['results' => [['key' => 'trailer_key']]],
            'vote_average' => 8.5,
            'poster_path' => '/path/to/poster.jpg',
        ]);

        $this->clientMock
            ->method('get')
            ->willReturnOnConsecutiveCalls($responseMockTopRated, $responseMockDetails);

        $movie = $this->repository->fetchTopRatedMovie();

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertEquals(123, $movie->getId());
        $this->assertEquals('Test Movie', $movie->getTitle());
        $this->assertEquals('2024-01-01', $movie->getReleaseDate());
        $this->assertEquals(100, $movie->getVotes());
    }

    public function testFetchMoviesByGenreReturnsArrayOfMovies(): void
    {
        $genreMoviesResponseMock = $this->createMock(ResponseInterface::class);
        $movieDetailsResponseMock = $this->createMock(ResponseInterface::class);

        $genreMoviesResponseMock->method('toArray')->willReturn([
            'total_results' => 1,
            'results' => [['id' => 123]],
        ]);

        $movieDetailsResponseMock->method('toArray')->willReturn([
            'id' => 123,
            'title' => 'Test Movie',
            'release_date' => '2024-01-01',
            'production_companies' => [['name' => 'Test Company']],
            'vote_count' => 100,
            'overview' => 'This is a test movie.',
            'videos' => ['results' => [['key' => 'trailer_key']]],
            'vote_average' => 8.5,
            'poster_path' => '/path/to/poster.jpg',
        ]);

        $this->clientMock
            ->method('get')
            ->willReturnOnConsecutiveCalls($genreMoviesResponseMock, $movieDetailsResponseMock);

        $movies = $this->repository->fetchMoviesByGenre(28);

        $this->assertCount(1, $movies);
        $this->assertInstanceOf(Movie::class, $movies[0]);
        $this->assertEquals(123, $movies[0]->getId());
        $this->assertEquals('Test Movie', $movies[0]->getTitle());
    }

    public function testFetchMoviesByTitleReturnsArrayOfMovies(): void
    {
        $searchMoviesResponseMock = $this->createMock(ResponseInterface::class);
        $movieDetailsResponseMock = $this->createMock(ResponseInterface::class);

        $searchMoviesResponseMock->method('toArray')->willReturn([
            'total_results' => 1,
            'results' => [['id' => 123]],
        ]);

        $movieDetailsResponseMock->method('toArray')->willReturn([
            'id' => 123,
            'title' => 'Test Movie',
            'release_date' => '2024-01-01',
            'production_companies' => [['name' => 'Test Company']],
            'vote_count' => 100,
            'overview' => 'This is a test movie.',
            'videos' => ['results' => [['key' => 'trailer_key']]],
            'vote_average' => 8.5,
            'poster_path' => '/path/to/poster.jpg',
        ]);

        $this->clientMock
            ->method('get')
            ->willReturnOnConsecutiveCalls($searchMoviesResponseMock, $movieDetailsResponseMock);

        $movies = $this->repository->fetchMoviesByTitle('Test Movie');

        $this->assertCount(1, $movies);
        $this->assertInstanceOf(Movie::class, $movies[0]);
        $this->assertEquals(123, $movies[0]->getId());
        $this->assertEquals('Test Movie', $movies[0]->getTitle());
    }

    public function testRateMovieReturnsTrueOnSuccess(): void
    {
        $this->clientMock
            ->method('post')
            ->willReturn($this->createMock(ResponseInterface::class));

        $result = $this->repository->rateMovie(123, 5);

        $this->assertTrue($result);
    }

    public function testRateMovieReturnsTrueOnException(): void
    {
        $this->clientMock
            ->method('post')
            ->willThrowException(new \Exception('API error'));

        $result = $this->repository->rateMovie(123, 5);

        $this->assertTrue($result);
    }
}
