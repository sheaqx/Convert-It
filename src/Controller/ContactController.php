<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_index')]
    public function index(Request $request, ContactService $contactService): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactService->sendContactMail($form);
            $this->addFlash('success', 'You\'re message was sent');
            return $this->redirectToRoute('home_index');
        }

        return $this->render('pages/contact/index.html.twig', [
            'controller_name' => 'ContactController', 'form' => $form->createView(),
        ]);
    }
}
