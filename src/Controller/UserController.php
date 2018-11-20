<?php

namespace App\Controller;

use App\Entity\MovieList;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Traits\JsonSerializerTrait;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations;

class UserController extends FOSRestController
{
    use JsonSerializerTrait;

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
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     */
    public function postUsersAction(User $user, ConstraintViolationListInterface $validationErrors) {
        if (count($validationErrors) > 0) {
            return $this->json('400: Bad request', 400);
        }

        $this->entityManager->persist($user);
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
