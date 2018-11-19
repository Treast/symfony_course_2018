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

    public function create(EntityManagerInterface $entityManager, Request $request) {
        $movie = new Movie();

        $data = json_decode($request->getContent());

        $movie->setTitle($data->title);
        $movie->setGenre($data->genre);
        $movie->setYear($data->year);

        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->json($movie);
    }

    public function show(Movie $movie) {
        return $this->json($movie);
    }

    public function update(EntityManagerInterface $entityManager, Request $request, Movie $movie) {
        $data = json_decode($request->getContent());

        $movie->setTitle($data->title);
        $movie->setGenre($data->genre);
        $movie->setYear($data->year);

        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->json($movie);
    }

    public function delete(Movie $movie) {
        $this->getDoctrine()->getManager()->remove($movie);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['success' => true]);
    }
}
