<?php

namespace App\UI\Controller;

use App\Application\Service\MovieInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/ratings")]
class RatingController extends AbstractController
{
    public function __construct(private readonly MovieInterface $movieService)
    {
    }

    #[Route("/{movieId}", name: "rate_movie", methods: ["POST"])]
    public function getTopRatedMovie(int $movieId, Request $request): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), true);
        if ($requestBody['rate'] === null) {
            return $this->json(['error' => 'Rate is required'], 400);
        }
        $result = $this->movieService->rateMovie($movieId, (int)$requestBody['rate']);

        if (!$result) {
            return $this->json(['error' => 'Movie was not rated'], 400);
        }
        return $this->json(['message' => 'Movie rated successfully']);
    }
}
