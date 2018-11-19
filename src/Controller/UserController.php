<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function list() {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->json($users);
    }

    public function show($user_id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);

        if(!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$user_id
            );
        }

        return $this->json($user);
    }

    public function update($user_id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);

        if(!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$user_id
            );
        }
        return $this->json($user);
    }

    public function delete($user_id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);

        if(!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$user_id
            );
        }

        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($user);
    }
}
