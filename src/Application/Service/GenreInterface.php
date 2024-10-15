<?php

namespace App\Application\Service;

use App\Application\DTO\GenreResponseDTO;

interface GenreInterface
{
    public function fetchGenresList(): array;
}
