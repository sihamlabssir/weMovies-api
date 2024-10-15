<?php

namespace App\Application\DTO;

class MovieResponseDTO
{
    public function __construct(
        private int $id,
        private string $title,
        private string $releaseYear,
        private string $productionCompanies,
        private int $votes,
        private string $description,
        private string $videoPath,
        private string $videoTitle,
        private float $stars,
        private string $posterPath
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

    public function getReleaseYear(): string
    {
        return $this->releaseYear;
    }

    public function getProductionCompanies(): string
    {
        return $this->productionCompanies;
    }

    public function getVotes(): int
    {
        return $this->votes;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getVideoPath(): string
    {
        return $this->videoPath;
    }

    public function getVideoTitle(): string
    {
        return $this->videoTitle;
    }

    public function getStars(): float
    {
        return $this->stars;
    }

    public function getPosterPath(): string
    {
        return $this->posterPath;
    }


    public static function toResponseDto(
        int $id,
        string $title,
        string $releaseYear,
        string $productionCompanies,
        int $votes,
        string $description,
        string $videoPath,
        string $videoTitle,
        float $stars,
        string $posterPath
    ): MovieResponseDTO {
        return new MovieResponseDTO(
            $id,
            $title,
            $releaseYear,
            $productionCompanies,
            $votes,
            $description,
            $videoPath,
            $videoTitle,
            $stars,
            $posterPath
        );
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'releaseYear' => $this->releaseYear,
            'productionCompanies' => $this->productionCompanies,
            'votes' => $this->votes,
            'description' => $this->description,
            'videoPath' => $this->videoPath,
            'videoTitle' => $this->videoTitle,
            'stars' => $this->stars,
            'posterPath' => $this->posterPath
        ];
    }
}
