<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Playlist;
use App\Entity\User;
use App\Repository\MovieRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Swagger\Annotations as SWG;

class PlaylistController extends FOSRestController
{

    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var PlaylistRepository  */
    private $playlistRepository;

    /** @var MovieRepository  */
    private $movieRepository;

    private $serializer;

    /**
     * PlaylistController constructor.
     * @param EntityManagerInterface $entityManager
     * @param PlaylistRepository $playlistRepository
     * @param MovieRepository $movieRepository
     */
    public function __construct(EntityManagerInterface $entityManager, PlaylistRepository $playlistRepository, MovieRepository $movieRepository)
    {
        $this->entityManager = $entityManager;
        $this->playlistRepository = $playlistRepository;
        $this->movieRepository = $movieRepository;
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @param User $user
     * @return Response
     * @SWG\Tag(name="Playlist")
     * @SWG\Parameter(name="user", type="string", in="path", description="User UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Return all playlists of an user.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Playlist::class))
     *     )
     * )
     */
    public function getPlaylistsAction(User $user) {
        return new Response($this->serializer->serialize($user->getPlaylists(), 'json'));
    }

    /**
     * @param User $user
     * @param Playlist $playlist
     * @return Response
     * @SWG\Tag(name="Playlist")
     * @SWG\Parameter(name="user", type="string", in="path", description="User UUID")
     * @SWG\Parameter(name="playlist", type="string", in="path", description="Playlist UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Return a specific playlist of an user.",
     *     @Model(type=Playlist::class)
     * )
     */
    public function getPlaylistAction(User $user, Playlist $playlist) {
        return new Response($this->serializer->serialize($playlist, 'json'));
    }

    /**
     * @ParamConverter("playlist", converter="fos_rest.request_body")
     * @param User $user
     * @param Playlist $playlist
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     * @SWG\Tag(name="Playlist")
     * @SWG\Parameter(name="user", type="string", in="path", description="User UUID")
     * @SWG\Parameter(name="playlist", type="string", in="path", description="Playlist UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Create and return a playlist to an user.",
     *     @Model(type=Playlist::class)
     * )
     */
    public function postPlaylistsAction(User $user, Playlist $playlist, ConstraintViolationListInterface $validationErrors) {
        if (count($validationErrors) > 0) {
            return $this->json('400: Bad request', 400);
        }

        $user->addPlaylist($playlist);

        $this->entityManager->persist($playlist);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($playlist, 'json'));
    }

    /**
     * @ParamConverter("newPlaylist", converter="fos_rest.request_body")
     * @param User $user
     * @param Playlist $playlist
     * @param Playlist $newPlaylist
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     * @SWG\Tag(name="Playlist")
     * @SWG\Parameter(name="user", type="string", in="path", description="User UUID")
     * @SWG\Parameter(name="playlist", type="string", in="path", description="Playlist UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Update the playlist of an user.",
     *     @Model(type=Playlist::class)
     * )
     */
    public function putPlaylistAction(User $user, Playlist $playlist, Playlist $newPlaylist, ConstraintViolationListInterface $validationErrors) {
        if (count($validationErrors) > 0) {
            return $this->json('400: Bad request', 400);
        }

        $playlist->setName($newPlaylist->getName());

        $this->entityManager->persist($playlist);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($playlist, 'json'));
    }

    /**
     * @param User $user
     * @param Playlist $playlist
     * @param Request $request
     * @return JsonResponse|Response
     * @SWG\Tag(name="Playlist")
     * @SWG\Parameter(name="user", type="string", in="path", description="User UUID")
     * @SWG\Parameter(name="playlist", type="string", in="path", description="Playlist UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Add a movie to a playlist of an user.",
     *     @Model(type=Playlist::class)
     * )
     */
    public function postPlaylistsMoviesAction(User $user, Playlist $playlist, Request $request) {
        $movie = $this->movieRepository->findByUuid($request->get('movie'));
        if (!$movie) {
            return $this->json('400: Bad request', 400);
        }

        $playlist->addMovie($movie);

        $this->entityManager->persist($playlist);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($playlist, 'json'));
    }

    /**
     * @param User $user
     * @param Playlist $playlist
     * @param Movie $movie
     * @return JsonResponse|Response
     * @SWG\Tag(name="Playlist")
     * @SWG\Parameter(name="user", type="string", in="path", description="User UUID")
     * @SWG\Parameter(name="playlist", type="string", in="path", description="Playlist UUID")
     * @SWG\Parameter(name="movie", type="string", in="path", description="Movie UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Delete a specific movie of a playlist of an user.",
     *     @Model(type=Playlist::class)
     * )
     */
    public function deletePlaylistsMoviesAction(User $user, Playlist $playlist, Movie $movie) {
        if (!$movie) {
            return $this->json('400: Bad request', 400);
        }

        $playlist->removeMovie($movie);

        $this->entityManager->persist($playlist);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($playlist, 'json'));
    }

    /**
     * @param User $user
     * @param Playlist $playlist
     * @return JsonResponse
     * @SWG\Tag(name="Playlist")
     * @SWG\Parameter(name="user", type="string", in="path", description="User UUID")
     * @SWG\Parameter(name="playlist", type="string", in="path", description="Playlist UUID")
     * @SWG\Response(
     *     response=200,
     *     description="Delete a playlist of an user.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="success", type="boolean")
     *     )
     * )
     */
    public function deletePlaylistAction(User $user, Playlist $playlist) {
        if(!$playlist) {
            return $this->json('400: Bad request', 400);
        }

        $this->entityManager->remove($playlist);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
