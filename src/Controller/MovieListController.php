<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\MovieList;
use App\Form\MovieListType;
use App\Repository\MovieListRepository;
use App\Repository\MovieRepository;
use App\Traits\JsonSerializerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieListController extends AbstractController
{
    use JsonSerializerTrait;

    public function index(MovieListRepository $movieListRepository) {
        $movies = $movieListRepository->findAll();
        return $this->serializeData($movies);
    }

    public function create(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator) {
        $movieList = new MovieList();
        $form = $this->createForm(MovieListType::class, $movieList);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($movieList);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movieList);
            $entityManager->flush();
            return $this->serializeData($movieList);
        }

        return $this->json('400: Bad request', 400);
    }

    public function show(MovieListRepository $movieListRepository, string $uuid) {
        $movieList = $movieListRepository->findByUuid($uuid);

        if(!$movieList) {
            return $this->json('404: Resource not found', 404);
        }

        return $this->serializeData($movieList);
    }

    public function update(EntityManagerInterface $entityManager, Request $request, MovieListRepository $movieListRepository, string $uuid, ValidatorInterface $validator) {
        $movieList = $movieListRepository->findByUuid($uuid);

        if(!$movieList) {
            return $this->json('404: Resource not found', 404);
        }

        $form = $this->createForm(MovieListType::class, $movieList);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($movieList);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movieList);
            $entityManager->flush();
            return $this->serializeData($movieList);
        }

        return $this->json('400: Bad request', 400);
    }

    public function delete(EntityManagerInterface $entityManager, MovieListRepository $movieListRepository, string $uuid) {
        $movieList = $movieListRepository->findByUuid($uuid);

        if(!$movieList) {
            return $this->json('404: Resource not found', 404);
        }

        $entityManager->remove($movieList);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
