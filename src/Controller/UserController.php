<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserController extends FOSRestController
{

    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var UserRepository  */
    private $userRepository;

    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function getUsersAction() {
        return new Response($this->serializer->serialize($this->userRepository->findAll(), 'json'));
    }

    public function getUserAction(User $user) {
        return new Response($this->serializer->serialize($user, 'json'));
    }

    /**
     * @ParamConverter("user", converter="fos_rest.request_body", options={"mapping": {"comment_slug": "slug"}})
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     */
    public function postUsersAction(User $user, ConstraintViolationListInterface $validationErrors) {
        if (count($validationErrors) > 0) {
            return $this->json('400: Bad request', 400);
        }

        $playlistPrefer = new Playlist();
        $playlistPrefer->setName('Mes préférés');

        $playlistToSee = new Playlist();
        $playlistToSee->setName('À voir');

        $user->addPlaylist($playlistPrefer);
        $user->addPlaylist($playlistToSee);

        $this->entityManager->persist($user);
        $this->entityManager->persist($playlistPrefer);
        $this->entityManager->persist($playlistToSee);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($user, 'json'));
    }

    /**
     * @ParamConverter("newUser", converter="fos_rest.request_body")
     * @param User $user
     * @param User $newUser
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     */
    public function putUserAction(User $user, User $newUser, ConstraintViolationListInterface $validationErrors) {
        if (count($validationErrors) > 0) {
            return $this->json('400: Bad request', 400);
        }

        $user->setUsername($newUser->getUsername());
        $user->setPassword($newUser->getPassword());
        $user->setEmail($newUser->getEmail());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($user, 'json'));
    }

    public function deleteUserAction(User $user) {
        if(!$user) {
            return $this->json('400: Bad request', 400);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
