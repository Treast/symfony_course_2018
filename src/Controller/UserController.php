<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function index(UserRepository $userRepository) {
        $users = $userRepository->findAll();
        return $this->json($users);
    }

    public function create(EntityManagerInterface $entityManager, Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json($user);
        }

        return $this->json('400: Bad request', 400);
    }

    public function show(User $user) {
        if(!$user) {
            return $this->json('400: Bad request', 400);
        }

        return $this->json($user);
    }

    public function update(EntityManagerInterface $entityManager, Request $request, User $user) {
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json($user);
        }

        return $this->json('400: Bad request', 400);
    }

    public function delete(EntityManagerInterface $entityManager, User $user) {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
