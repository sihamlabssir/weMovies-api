<?php

namespace App\Tests\Application\DTO;

use App\Application\DTO\GenreResponseDTO;
use PHPUnit\Framework\TestCase;

class GenreResponseDTOTest extends TestCase
{
    public function testGetters()
    {
        $genreDto = new GenreResponseDTO(1, 'Action');

        $this->assertEquals(1, $genreDto->getId());
        $this->assertEquals('Action', $genreDto->getName());
    }

    public function testToResponseDto()
    {
        $genreDto = GenreResponseDTO::toResponseDto(2, 'Drama');

        $this->assertInstanceOf(GenreResponseDTO::class, $genreDto);
        $this->assertEquals(2, $genreDto->getId());
        $this->assertEquals('Drama', $genreDto->getName());
    }

    public function testToArray()
    {
        $genreDto = new GenreResponseDTO(3, 'Comedy');
        $expectedArray = [
            'id' => 3,
            'name' => 'Comedy',
        ];

        $this->assertEquals($expectedArray, $genreDto->toArray());
    }
}
