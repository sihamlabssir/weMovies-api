<?php

namespace App\Application\DTO;

class GenreResponseDTO
{
    public function __construct(
        private int $id,
        private string $name
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function toResponseDto(
        int $id,
        string $name
    ): GenreResponseDTO {
        return new GenreResponseDTO($id, $name);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
