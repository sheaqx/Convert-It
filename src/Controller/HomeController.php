<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\UploadType;
use App\Service\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(EntityManagerInterface $manager, Request $request, Upload $imageUpload): Response
    {
        $upload = new Picture();
        $uploadForm = $this->createForm(UploadType::class, $upload);
        $uploadForm->handleRequest($request);
        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $uploadedFile = $uploadForm->get('name')->getData();
            $uploadFileName = $imageUpload->imageUpload($uploadedFile);
            //Sending form info to database
            $upload->setName($uploadFileName)
                ->setTag($upload->getTag())
                ->setDescription($upload->getDescription())
                ->setSlug($upload->getName())
                ->setUser($upload->getUser());
            $manager->persist($upload);
            $manager->flush();
        }
        return $this->renderForm('pages/home/index.html.twig', ['loggedUser' => [""], '', 'form' => $uploadForm]);
    }
}
