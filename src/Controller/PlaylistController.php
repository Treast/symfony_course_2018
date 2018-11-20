<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\User;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PlaylistController extends FOSRestController
{

    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var PlaylistRepository  */
    private $playlistRepository;

    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, PlaylistRepository $playlistRepository)
    {
        $this->entityManager = $entityManager;
        $this->playlistRepository = $playlistRepository;
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @param User $user
     * @return Response
     */
    public function getPlaylistsAction(User $user) {
        return new Response($this->serializer->serialize($user->getPlaylists(), 'json'));
    }

    public function getPlaylistAction(User $user, Playlist $playlist) {
        return new Response($this->serializer->serialize($playlist, 'json'));
    }

    /**
     * @ParamConverter("playlist", converter="fos_rest.request_body")
     * @param User $user
     * @param Playlist $playlist
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
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

    public function deletePlaylistAction(User $user, Playlist $playlist) {
        if(!$playlist) {
            return $this->json('400: Bad request', 400);
        }

        $this->entityManager->remove($playlist);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
