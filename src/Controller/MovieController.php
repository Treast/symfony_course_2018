<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MovieController extends FOSRestController
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var MovieRepository  */
    private $movieRepository;

    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, MovieRepository $movieRepository)
    {
        $this->entityManager = $entityManager;
        $this->movieRepository = $movieRepository;
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function getMoviesAction() {
        return new Response($this->serializer->serialize($this->movieRepository->findAll(), 'json'));
    }

    public function getMovieAction(Movie $movie) {
        return new Response($this->serializer->serialize($movie, 'json'));
    }

    /**
     * @ParamConverter("movie", converter="fos_rest.request_body")
     * @param Movie $movie
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     */
    public function postMoviesAction(Movie $movie, ConstraintViolationListInterface $validationErrors) {
        if (count($validationErrors) > 0) {
            return $this->json('400: Bad request', 400);
        }

        $this->entityManager->persist($movie);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($movie, 'json'));
    }

    /**
     * @ParamConverter("newMovie", converter="fos_rest.request_body")
     * @param Movie $movie
     * @param Movie $newMovie
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     */
    public function putMovieAction(Movie $movie, Movie $newMovie, ConstraintViolationListInterface $validationErrors) {
        if (count($validationErrors) > 0) {
            return $this->json('400: Bad request', 400);
        }

        $movie->setTitle($newMovie->getTitle());
        $movie->setDescription($newMovie->getDescription());
        $movie->setGenre($newMovie->getGenre());
        $movie->setImageUrl($newMovie->getImageUrl());
        $movie->setYear($newMovie->getYear());

        $this->entityManager->persist($movie);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($movie, 'json'));
    }

    public function deleteMovieAction(Movie $movie) {
        if(!$movie) {
            return $this->json('400: Bad request', 400);
        }

        $this->entityManager->remove($movie);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
