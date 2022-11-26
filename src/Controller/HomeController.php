<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Form\UploadType;
use App\Repository\UserRepository;
use App\Service\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(
        EntityManagerInterface $manager,
        Request $request,
        Upload $imageUpload,
        UserRepository $userRepository
    ): Response {

        $currentUser = $this->getUser();
        $upload = new Picture();
        $uploadForm = $this->createForm(UploadType::class, $upload);
        $uploadForm->handleRequest($request);
        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $uploadedFile = $uploadForm->get('name')->getData();
            // temporary path for uploadedFile must be fixed when creating convert method
            $path = 'files/temp/';
            $uploadFileName = $imageUpload->setUploadDestination($path);
            $uploadFileName = $imageUpload->imageUpload($uploadedFile);
            $user = $userRepository->find($currentUser);
            //Sending form info to database
            $upload->setName($path . $uploadFileName)
                ->setTag($upload->getTag())
                ->setDescription($upload->getDescription())
                ->setSlug($upload->getName())
                ->setUser($user);
            $manager->persist($upload);
            $manager->flush();
            $this->addFlash('success', 'your picture has been uploaded.');
            return $this->redirectToRoute('home_index');
        }
        return $this->renderForm('pages/home/index.html.twig', ['form' => $uploadForm]);
    }
}
