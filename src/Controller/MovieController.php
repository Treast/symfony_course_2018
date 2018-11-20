<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Traits\JsonSerializerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    use JsonSerializerTrait;

    public function index(MovieRepository $movieRepository) {
        $movies = $movieRepository->findAll();
        return $this->serializeData($movies);
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
            return $this->serializeData($movie);
        }

        return $this->json('400: Bad request', 400);
    }

    public function show(MovieRepository $movieRepository, string $uuid) {
        $movie = $movieRepository->findByUuid($uuid);

        if(!$movie) {
            return $this->json('404: Resource not found', 404);
        }

        return $this->serializeData($movie);
    }

    public function update(EntityManagerInterface $entityManager, Request $request, MovieRepository $movieRepository, string $uuid, ValidatorInterface $validator) {
        $movie = $movieRepository->findByUuid($uuid);

        if(!$movie) {
            return $this->json('404: Resource not found', 404);
        }

        $form = $this->createForm(MovieType::class, $movie);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($movie);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();
            return $this->serializeData($movie);
        }

        return $this->json('400: Bad request', 400);
    }

    public function delete(EntityManagerInterface $entityManager, MovieRepository $movieRepository, string $uuid) {
        $movie = $movieRepository->findByUuid($uuid);

        if(!$movie) {
            return $this->json('404: Resource not found', 404);
        }

        $entityManager->remove($movie);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
