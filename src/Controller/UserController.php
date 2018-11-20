<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Traits\JsonSerializerTrait;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends FOSRestController
{
    use JsonSerializerTrait;

    /** @var EntityManagerInterface  */
    private $entityManager;
    /** @var UserRepository  */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function getUsersAction() {
        return new JsonResponse($this->userRepository->findAll());
    }

    public function postUser(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($user);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->serializeData($user);
        }

        return $this->json('400: Bad request', 400);
    }

    public function putUser(EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository, string $uuid, ValidatorInterface $validator) {
        $user = $userRepository->findByUuid($uuid);

        if(!$user) {
            return $this->json('400: Bad request', 400);
        }

        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($user);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->serializeData($user);
        }

        return $this->json('400: Bad request', 400);
    }

    public function deleteUser(EntityManagerInterface $entityManager, UserRepository $userRepository, string $uuid) {
        $user = $userRepository->findByUuid($uuid);

        if(!$user) {
            return $this->json('400: Bad request', 400);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
