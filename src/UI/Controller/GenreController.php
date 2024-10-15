<?php

namespace App\UI\Controller;

use App\Application\Service\GenreInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/genres")]
class GenreController extends AbstractController
{
    public function __construct(private readonly GenreInterface $genreService)
    {
    }


    #[Route("/", name: "genres_list", methods: ["GET"])]
    public function getTopRatedMovie(): JsonResponse
    {
        $genresList = $this->genreService->fetchGenresList();

        return $this->json($genresList);
    }
}
