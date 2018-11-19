<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function index(UserRepository $userRepository) {
        $movies = $userRepository->findAll();
        return $this->json($movies);
    }

    public function create(UserRepository $userRepository, Request $request) {
        return $this->json($userRepository->createFromRequest($request));
    }

    public function show(User $user) {
        return $this->json($user);
    }

    public function update(UserRepository $userRepository, Request $request, User $user) {
        return $this->json($userRepository->updateFromRequest($request, $user));
    }

    public function delete(EntityManagerInterface $entityManager, User $user) {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
