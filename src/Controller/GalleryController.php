<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'gallery_index', methods: ['GET'])]
    //Pagination
    public function index(
        PictureRepository $pictureRepository,
        Request $request
    ): Response {

        $page = (int) $request->query->get('page', '1');
        $limit = 8;
        $pagesCount = ceil(count($pictureRepository->findAll()) / $limit);
        $pages = range(1, $pagesCount);
        $prevPages = range($page - 2, $page);
        $nextPages = range($page + 1, $page + 2);
        $data = $pictureRepository->findBy([], [], $limit, ($limit * ($page - 1)));


        return $this->render('pages/gallery/index.html.twig', [
            'pictures' => $data,
            'prevPages' => $prevPages,
            'nextPages' => $nextPages,
            'pages' => $pages,
            'page' => $page,
            'latest' => (int) $pagesCount,
            'pathRedirection' => 'gallery_index'
        ]);
    }

    #[Route('/show/{picture}', name: 'picture_show', methods: ['GET'])]
    public function show(Picture $picture, PictureRepository $pictureRepository): Response
    {
        $picture = $pictureRepository->find($picture);
        $extension = $picture->getName();
        $extension = explode('.', $extension);
        $extension = $extension[1];
        return $this->render('pages/gallery/show.html.twig', ['picture' => $picture, 'extension' => $extension]);
    }
}
