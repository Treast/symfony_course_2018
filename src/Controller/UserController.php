<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function index(UserRepository $userRepository) {
        $movies = $userRepository->findAll();
        return $this->json($movies);
    }

    public function show(UserRepository $userRepository, User $user) {
        return $this->json($user);
    }

    public function update(UserRepository $userRepository, User $user) {
        return $this->json($user);
    }

    public function delete(User $user) {
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['success' => true]);
    }
}
