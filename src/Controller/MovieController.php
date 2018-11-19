<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends AbstractController
{
    public function index(MovieRepository $movieRepository) {
        $movies = $movieRepository->findAll();
        return $this->json($movies);
    }

    public function create(MovieRepository $movieRepository, Request $request) {
        $movie = new Movie();
        return $this->json($movieRepository->updateFromRequest($request, $movie));
    }

    public function show(Movie $movie) {
        return $this->json($movie);
    }

    public function update(Request $request, MovieRepository $movieRepository, Movie $movie) {
        return $this->json($movieRepository->updateFromRequest($request, $movie));
    }

    public function delete(EntityManagerInterface $entityManager, Movie $movie) {
        $entityManager->remove($movie);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
