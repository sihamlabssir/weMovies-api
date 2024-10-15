<?php

namespace App\Tests\Application\DTO;

use App\Application\DTO\MovieResponseDTO;
use PHPUnit\Framework\TestCase;

class MovieResponseDTOTest extends TestCase
{
    public function testGetters()
    {
        $movieDto = new MovieResponseDTO(
            1,
            'Inception',
            '2010',
            'Warner Bros.',
            1000,
            'A mind-bending thriller',
            'https://example.com/video.mp4',
            'Inception Trailer',
            4.8,
            'https://example.com/poster.jpg'
        );

        $this->assertEquals(1, $movieDto->getId());
        $this->assertEquals('Inception', $movieDto->getTitle());
        $this->assertEquals('2010', $movieDto->getReleaseYear());
        $this->assertEquals('Warner Bros.', $movieDto->getProductionCompanies());
        $this->assertEquals(1000, $movieDto->getVotes());
        $this->assertEquals('A mind-bending thriller', $movieDto->getDescription());
        $this->assertEquals('https://example.com/video.mp4', $movieDto->getVideoPath());
        $this->assertEquals('Inception Trailer', $movieDto->getVideoTitle());
        $this->assertEquals(4.8, $movieDto->getStars());
        $this->assertEquals('https://example.com/poster.jpg', $movieDto->getPosterPath());
    }

    public function testToResponseDto()
    {
        $movieDto = MovieResponseDTO::toResponseDto(
            2,
            'Avatar',
            '2009',
            '20th Century Fox',
            500,
            'A visually stunning movie',
            'https://example.com/avatar.mp4',
            'Avatar Trailer',
            4.5,
            'https://example.com/avatar-poster.jpg'
        );

        $this->assertInstanceOf(MovieResponseDTO::class, $movieDto);
        $this->assertEquals(2, $movieDto->getId());
        $this->assertEquals('Avatar', $movieDto->getTitle());
        $this->assertEquals('2009', $movieDto->getReleaseYear());
        $this->assertEquals('20th Century Fox', $movieDto->getProductionCompanies());
        $this->assertEquals(500, $movieDto->getVotes());
        $this->assertEquals('A visually stunning movie', $movieDto->getDescription());
        $this->assertEquals('https://example.com/avatar.mp4', $movieDto->getVideoPath());
        $this->assertEquals('Avatar Trailer', $movieDto->getVideoTitle());
        $this->assertEquals(4.5, $movieDto->getStars());
        $this->assertEquals('https://example.com/avatar-poster.jpg', $movieDto->getPosterPath());
    }

    public function testToArray()
    {
        $movieDto = new MovieResponseDTO(
            3,
            'The Matrix',
            '1999',
            'Warner Bros.',
            1500,
            'A sci-fi classic',
            'https://example.com/matrix.mp4',
            'The Matrix Trailer',
            4.9,
            'https://example.com/matrix-poster.jpg'
        );

        $expectedArray = [
            'id' => 3,
            'title' => 'The Matrix',
            'releaseYear' => '1999',
            'productionCompanies' => 'Warner Bros.',
            'votes' => 1500,
            'description' => 'A sci-fi classic',
            'videoPath' => 'https://example.com/matrix.mp4',
            'videoTitle' => 'The Matrix Trailer',
            'stars' => 4.9,
            'posterPath' => 'https://example.com/matrix-poster.jpg'
        ];

        $this->assertEquals($expectedArray, $movieDto->toArray());
    }
}
