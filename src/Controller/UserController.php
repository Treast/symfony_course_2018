<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Traits\JsonSerializerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    use JsonSerializerTrait;

    public function index(UserRepository $userRepository) {
        $users = $userRepository->findAll();
        return $this->serializeData($users);
    }

    public function create(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator) {
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

    public function show(UserRepository $userRepository, string $uuid) {
        $user = $userRepository->findByUuid($uuid);
        if(!$user) {
            return $this->json('400: Bad request', 400);
        }

        return $this->serializeData($user);
    }

    public function update(EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository, string $uuid, ValidatorInterface $validator) {
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

    public function delete(EntityManagerInterface $entityManager, UserRepository $userRepository, string $uuid) {
        $user = $userRepository->findByUuid($uuid);

        if(!$user) {
            return $this->json('400: Bad request', 400);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
