<?php

namespace App\Domain\Entity;

class Movie
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly string $releaseDate,
        private readonly array $productionCompanies,
        private readonly int $votes,
        private readonly string $overview,
        private readonly array $videos,
        private readonly float $averageRating,
        private readonly string $posterKey
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function getProductionCompanies(): array
    {
        return $this->productionCompanies;
    }

    public function getVotes(): int
    {
        return $this->votes;
    }

    public function getOverview(): string
    {
        return $this->overview;
    }

    public function getVideos(): array
    {
        return $this->videos;
    }

    public function getAverageRating(): float
    {
        return $this->averageRating;
    }

    public function getPosterKey(): string
    {
        return $this->posterKey;
    }
}
