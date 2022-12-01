<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Form\UploadType;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use App\Service\Convert;
use App\Service\Upload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(
        PictureRepository $pictureRepository,
        Request $request,
        Upload $imageUpload,
        UserRepository $userRepository,
        Convert $convert,
    ): Response {

        $currentUser = $this->getUser();
        $user = $userRepository->find($currentUser);
        $picture = new Picture();
        $uploadForm = $this->createForm(UploadType::class, $picture);
        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            //get picture infos and mv to temp folder
            $uploadedFile = $uploadForm->get('name')->getData();
            $uploadFileName = $imageUpload->imageUpload($uploadedFile);

            //get form extension info and file extension info
            $dropdown = $uploadForm->get('convertTo')->getData();
            $fileExtension = $uploadedFile->getClientOriginalExtension();

            if ($dropdown === 1) {
                $this->addFlash('success', 'please chose a format to convert your picture into');
                return $this->redirectToRoute('home_index');
            }

            //prepare converted file name
            $convertedFile = '';


            // png treatment
            if ($fileExtension === 'png') {
                if ($dropdown !== 3) {
                    $convertedFile = $convert->convertPngFromDropdown($dropdown, $uploadFileName);
                } else {
                    $this->addFlash('success', 'input and outpout formats are indentical');
                    return $this->redirectToRoute('home_index');
                }
            }

            //jpg/jpeg treatment
            if (($fileExtension === 'jpg' || $fileExtension === 'jpeg')) {
                if ($dropdown !== 4) {
                    $convertedFile = $convert->convertJpgFromDropdown($dropdown, $uploadFileName);
                } else {
                    $this->addFlash('success', 'input and outpout formats are indentical');
                    return $this->redirectToRoute('home_index');
                }
            }

            //webp treatment
            if (($fileExtension === 'webp')) {
                if ($dropdown !== 2) {
                    $convertedFile = $convert->convertWebpFromDropdown($dropdown, $uploadFileName);
                } else {
                    $this->addFlash('success', 'input and outpout formats are indentical');
                    return $this->redirectToRoute('home_index');
                }
            }

            $picture->setName($convertedFile)
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
