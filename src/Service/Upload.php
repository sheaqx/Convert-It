<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Upload
{
    public function __construct(private string $uploadDestination, private SluggerInterface $slugger)
    {
        $this->uploadDestination = $uploadDestination;
        $this->slugger = $slugger;
    }

    public function imageUpload(UploadedFile $uploadedFile): string
    {
        $originaleFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originaleFileName);
        $newFileName = $safeFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        //send image to temp folder
        $uploadedFile->move(
            $this->getUploadDestination(),
            $newFileName
        );
        return $newFileName;
    }
    public function getUploadDestination(): string
    {
        return $this->uploadDestination;
    }
}
