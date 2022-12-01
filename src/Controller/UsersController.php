<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users_index')]
    public function index(UserRepository $users, Request $request): Response
    {
        $page = (int) $request->query->get('page', '1');
        $limit = 4;
        $pagesCount = ceil(count($users->findAll()) / $limit);
        $pages = range(1, $pagesCount);
        $prevPages = range($page - 2, $page);
        $nextPages = range($page + 1, $page + 2);
        $data = $users->findBy([], [], $limit, ($limit * ($page - 1)));


        return $this->render('pages/users/index.html.twig', [
            'users' => $data,
            'prevPages' => $prevPages,
            'nextPages' => $nextPages,
            'pages' => $pages,
            'page' => $page,
            'latest' => (int) $pagesCount,
            'pathRedirection' => 'users_index'
        ]);
    }
    //     return $this->render('pages/users/index.html.twig', [
    //         'users' => $users->findAll(),
    //     ]);
    // }

    #[Route('/users/show/{id}', name: 'users_show')]
    public function show(int $id, UserRepository $users): Response
    {
        $user = $users->findOneBy(['id' => $id]);
        return $this->render('pages/users/show.html.twig', [
            'user' => $user
        ]);
    }
}
