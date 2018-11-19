<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    public function index(MovieRepository $movieRepository) {
        $movies = $movieRepository->findAll();
        return $this->json($movies);
    }

    public function show(Movie $movie) {
        return $this->json($movie);
    }

    public function update(MovieRepository $movieRepository, Movie $movie) {
        return $this->json($movie);
    }

    public function delete(Movie $movie) {
        $this->getDoctrine()->getManager()->remove($movie);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['success' => true]);
    }
}
