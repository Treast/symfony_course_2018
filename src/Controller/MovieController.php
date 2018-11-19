<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    public function index(MovieRepository $movieRepository) {
        $movies = $movieRepository->findAll();
        return $this->json($movies);
    }

    public function create(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator) {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($movie);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();
            return $this->json($movie);
        }

        return $this->json('400: Bad request', 400);
    }

    public function show(Movie $movie) {
        if(!$movie) {
            return $this->json('400: Bad request', 400);
        }

        return $this->json($movie);
    }

    public function update(EntityManagerInterface $entityManager, Request $request, Movie $movie, ValidatorInterface $validator) {
        $form = $this->createForm(MovieType::class, $movie);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($movie);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();
            return $this->json($movie);
        }

        return $this->json('400: Bad request', 400);
    }

    public function delete(EntityManagerInterface $entityManager, Movie $movie) {
        $entityManager->remove($movie);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
