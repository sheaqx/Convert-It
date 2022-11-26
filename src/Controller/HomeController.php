<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Form\UploadType;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use App\Service\Upload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(
        PictureRepository $pictureRepository,
        Request $request,
        Upload $imageUpload,
        UserRepository $userRepository
    ): Response {

        $currentUser = $this->getUser();
        $picture = new Picture();
        $uploadForm = $this->createForm(UploadType::class, $picture);
        $uploadForm->handleRequest($request);
        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $uploadedFile = $uploadForm->get('name')->getData();
            $uploadFileName = $imageUpload->imageUpload($uploadedFile);
            $user = $userRepository->find($currentUser);
            $picture->setName(Upload::TEMP_PATH . $uploadFileName)
                ->setTag($picture->getTag())
                ->setDescription($picture->getDescription())
                ->setSlug($picture->getName())
                ->setUser($user);
            $pictureRepository->save($picture, true);
            $this->addFlash('success', 'your picture has been uploaded.');
            return $this->redirectToRoute('home_index');
        }
        return $this->renderForm('pages/home/index.html.twig', ['form' => $uploadForm]);
    }
}
