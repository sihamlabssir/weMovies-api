<?php

namespace App\Tests\Application\Service;

use App\Application\DTO\GenreResponseDTO;
use App\Application\Service\CacheServiceInterface;
use App\Application\Service\GenreService;
use App\Domain\Entity\Genre;
use App\Domain\Repository\GenreRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class GenreServiceTest extends TestCase
{
    private GenreRepositoryInterface $genreRepositoryMock;
    private CacheServiceInterface $cacheServiceMock;
    private GenreService $genreService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->genreRepositoryMock = $this->createMock(GenreRepositoryInterface::class);
        $this->cacheServiceMock = $this->createMock(CacheServiceInterface::class);
        $this->genreService = new GenreService($this->genreRepositoryMock, $this->cacheServiceMock);
    }

    public function testFetchGenresListReturnsEmptyArrayWhenNoGenres(): void
    {
        $this->genreRepositoryMock
            ->method('fetchGenreList')
            ->willReturn([]);

        $result = $this->genreService->fetchGenresList();

        $this->assertSame([], $result);
    }

    public function testFetchGenresListReturnsGenreDtoArray(): void
    {
        $genre1 = new Genre(1, 'Action');
        $genre2 = new Genre(2, 'Comedy');

        $this->genreRepositoryMock
            ->method('fetchGenreList')
            ->willReturn([$genre1, $genre2]);

        $result = $this->genreService->fetchGenresList();

        $expectedResult = [
            (new GenreResponseDTO(1, 'Action'))->toArray(),
            (new GenreResponseDTO(2, 'Comedy'))->toArray(),
        ];

        $this->assertSame($expectedResult, $result);
    }
}
