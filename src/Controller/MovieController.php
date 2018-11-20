<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Swagger\Annotations as SWG;

class MovieController extends FOSRestController
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var MovieRepository  */
    private $movieRepository;

    /** @var \JMS\Serializer\Serializer  */
    private $serializer;

    /**
     * MovieController constructor.
     * @param EntityManagerInterface $entityManager
     * @param MovieRepository $movieRepository
     */
    public function __construct(EntityManagerInterface $entityManager, MovieRepository $movieRepository)
    {
        $this->entityManager = $entityManager;
        $this->movieRepository = $movieRepository;
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @return Response
     * @SWG\Tag(name="Movie")
     * @SWG\Response(
     *     response=200,
     *     description="Return all the movies.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Movie::class))
     *     )
     * )
     */
    public function getMoviesAction() {
        return new Response($this->serializer->serialize($this->movieRepository->findAll(), 'json'));
    }

    /**
     * @param Movie $movie
     * @return Response
     * @SWG\Tag(name="Movie")
     * @SWG\Parameter(name="movie", type="string", in="path", description="Movie UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Return a specific movie.",
     *     @Model(type=Movie::class)
     * )
     */
    public function getMovieAction(Movie $movie) {
        return new Response($this->serializer->serialize($movie, 'json'));
    }

    /**
     * @ParamConverter("movie", converter="fos_rest.request_body")
     * @param Movie $movie
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     * @SWG\Tag(name="Movie")
     * @SWG\Parameter(name="movie", type="string", in="path", description="Movie UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Create and return a movie.",
     *     @Model(type=Movie::class)
     * )
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
     * @SWG\Tag(name="Movie")
     * @SWG\Parameter(name="movie", type="string", in="path", description="Movie UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Update a movie.",
     *     @Model(type=Movie::class)
     * )
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

    /**
     * @param Movie $movie
     * @return JsonResponse
     * @SWG\Tag(name="Movie")
     * @SWG\Parameter(name="movie", type="string", in="path", description="Movie UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Delete a movie.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="success", type="boolean")
     *     )
     * )
     */
    public function deleteMovieAction(Movie $movie) {
        if(!$movie) {
            return $this->json('400: Bad request', 400);
        }

        $this->entityManager->remove($movie);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
