<?php

namespace App\UI\Controller;

use App\Application\Service\MovieInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/movies")]
class MoviesController extends AbstractController
{
    public function __construct(private readonly MovieInterface $movieService)
    {
    }

    #[Route("/top-rated", name: "top_rated_movie", methods: ["GET"])]
    public function getTopRatedMovie(): JsonResponse
    {
        $movie = $this->movieService->fetchTopRatedMovie();

        return $this->json($movie->toArray());
    }

    #[Route("/genres/{id}", name: "movies_by_genre", methods: ["GET"])]
    public function getMoviesByGenre(int $id): JsonResponse
    {
        $moviesList = $this->movieService->fetchMoviesByGenre($id);

        return $this->json($moviesList);
    }

    #[Route("/search", name: "search_movies_by_title", methods: ["GET"])]
    public function searchMoviesByTitle(Request $request): JsonResponse
    {
        $title = $request->query->get('query');

        if (!$title) {
            return $this->json([], 400);
        }

        $moviesList = $this->movieService->fetchMoviesByTitle($title);

        return $this->json($moviesList);
    }
}
