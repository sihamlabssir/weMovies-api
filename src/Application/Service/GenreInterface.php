<?php

namespace App\Application\Service;

use App\Application\DTO\GenreResponseDTO;

interface GenreInterface
{
    /**
     * @return GenreResponseDTO[]
     */
    public function fetchGenresList(): array;
}
