<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ActorController extends AbstractController
{
    public function index(ActorRepository $actorRepository) {
        $actors = $actorRepository->findAll();
        return $this->json($actors);
    }

    public function create(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator) {
        $actor = new User();
        $form = $this->createForm(ActorType::class, $actor);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($actor);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($actor);
            $entityManager->flush();
            return $this->json($actor);
        }

        return $this->json('400: Bad request', 400);
    }

    public function show(ActorRepository $userRepository, string $uuid) {
        $actor = $userRepository->findByUuid($uuid);
        if(!$actor) {
            return $this->json('400: Bad request', 400);
        }

        return $this->json($actor);
    }

    public function update(EntityManagerInterface $entityManager, Request $request, ActorRepository $actorRepository, string $uuid, ValidatorInterface $validator) {
        $actor = $actorRepository->findByUuid($uuid);

        if(!$actor) {
            return $this->json('400: Bad request', 400);
        }

        $form = $this->createForm(ActorType::class, $actor);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $errors = $validator->validate($actor);

        if (count($errors) === 0 && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($actor);
            $entityManager->flush();
            return $this->json($actor);
        }

        return $this->json('400: Bad request', 400);
    }

    public function delete(EntityManagerInterface $entityManager, ActorRepository $actorRepository, string $uuid) {
        $actor = $actorRepository->findByUuid($uuid);

        if(!$actor) {
            return $this->json('400: Bad request', 400);
        }

        $entityManager->remove($actor);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
