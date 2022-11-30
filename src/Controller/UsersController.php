<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users_index')]
    public function index(UserRepository $users): Response
    {
        return $this->render('pages/users/index.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    #[Route('/users/show/{id}', name: 'users_show')]
    public function show($id, UserRepository $users): Response
    {
        $user = $users->findOneBy(['id' => $id]);
        return $this->render('pages/users/show.html.twig', [
            'user' => $user
        ]);
    }
}
