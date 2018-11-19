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

    public function create(EntityManagerInterface $entityManager, Request $request) {
        $user = new User();

        $data = json_decode($request->getContent());

        $user->setUsername($data->username);
        $user->setPassword($data->password);
        $user->setEmail($data->email);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user);
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
