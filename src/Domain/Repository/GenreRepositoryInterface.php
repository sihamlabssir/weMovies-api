<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Genre;

interface GenreRepositoryInterface
{
    /**
     * @return Genre[]
     */
    public function fetchGenreList(): array;
}
