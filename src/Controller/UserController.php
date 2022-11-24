<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SuspendAccount;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('pages/user/index.html.twig', [
            'controller_name' => 'UserController',
            'pictures' => []
        ]);
    }

    #[Route('/suspend/{id}', name: 'suspend')]
    public function suspend(
        int $id,
        UserRepository $userRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {


        $user = $userRepository->find($id);
        $form = $this->createForm(SuspendAccount::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setActive(false);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_logout');
        }
        return $this->render('pages/user/suspend.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
