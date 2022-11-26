<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Form\SuspendAccountType;
use App\Form\UploadProfilePictureType;
use App\Form\UserEditBioFormType;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use App\Service\Upload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(PictureRepository $pictureRepository, Upload $upload): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['pictureID']);
            $pictureToDelete = $pictureRepository->find($id);
            $pictureToDeleteName = $pictureToDelete->getName();
            $pictureRepository->remove($pictureToDelete, true);
            $upload->deleteOldPicture($pictureToDeleteName, Upload::TEMP_PATH);
            $this->addFlash('success', 'Picture succeessfully deleted.');
        }

        return $this->render('pages/user/index.html.twig');
    }

    #[Route('/suspend', name: 'suspend')]
    public function suspend(
        UserRepository $userRepository,
        Request $request,
        TokenStorageInterface $tokenStorage,
    ): Response {
        /** @var User */
        $user = $this->getUser();
        $form = $this->createForm(SuspendAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->remove($user, true);
            $request->getSession()->invalidate();
            $tokenStorage->setToken(null);
            return $this->redirectToRoute('home_index');
        }
        return $this->render('pages/user/suspend.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/editBio', name: 'editBio')]
    public function editBio(
        Request $request,
        UserRepository $userRepository,
    ): Response {
        /** @var User */
        $user = $this->getUser();
        $form = $this->createForm(UserEditBioFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setBio($form->get('bio')->getData());
            $userRepository->save($user, true);
            return $this->redirectToRoute('user_index');
        }

        return $this->render('pages/user/editBio.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/editProfilePicture', name: 'editProfilePicture')]
    public function editProfilePictureAction(
        Request $request,
        Upload $upload,
    ): Response {

        /** @var User */
        $user = $this->getUser();
        $form = $this->createForm(UploadProfilePictureType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $upload->uploadProfilePicture($form, $user);
            $this->addFlash('success', 'your profile picture has been updated.');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('pages/user/editProfilePicture.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
