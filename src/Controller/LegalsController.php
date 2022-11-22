<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalsController extends AbstractController
{
    #[Route('/legals', name: 'legals_index')]
    public function index(): Response
    {
        return $this->render('pages/legals/index.html.twig', [
            'controller_name' => 'LegalsController',
        ]);
    }
}
