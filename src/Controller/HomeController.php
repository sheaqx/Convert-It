<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\UploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(EntityManagerInterface $manager, Request $request, SluggerInterface $slugger): Response
    {
        $upload = new Picture();
        $uploadForm = $this->createForm(UploadType::class, $upload);
        $uploadForm->handleRequest($request);
        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $uploadedFile = $uploadForm->get('name')->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/files/temp';
            $originaleFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            //change image name
            $safeFileName = $slugger->slug($originaleFileName);
            $newFileName = $safeFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            //send image to temp folder
            $uploadedFile->move(
                $destination,
                $newFileName
            );
            //Sending form info to database
            $upload->setName($newFileName);
            $upload->setTag($upload->getTag());
            $upload->setDescription($upload->getDescription());
            $upload->setSlug($upload->getName());
            $upload->setUser($upload->getUser());
            $manager->persist($upload);
            $manager->flush();
            dd($upload);
        }

        return $this->renderForm('pages/home/index.html.twig', ['loggedUser' => [""], '', 'form' => $uploadForm]);
    }
}
