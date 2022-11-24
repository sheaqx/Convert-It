<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\UploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(Request $request, SluggerInterface $slugger): Response
    {
        $upload = new Picture();
        $uploadForm = $this->createForm(UploadType::class, $upload);
        $uploadForm->handleRequest($request);
        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            // dd($uploadForm['name']->getData());
            $uploadedFile = $uploadForm->get('name')->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/files/temp';
            $originaleFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originaleFileName);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            //$name->setName($newFilename);
        }

        return $this->renderForm('pages/home/index.html.twig', ['loggedUser' => [""], '', 'form' => $uploadForm]);
    }
}
