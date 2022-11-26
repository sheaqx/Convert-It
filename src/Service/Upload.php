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

    public function profilePictureUpload(UploadedFile $uploadedFile): string
    {
        $originaleFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originaleFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move(
            $this->getUploadDestination(),
            $newFileName
        );
        return $newFileName;
    }


    public function setUploadDestination(string $uploadDestination): self
    {
        $this->uploadDestination = $uploadDestination;

        return $this;
    }

    public function getUploadDestination(): string
    {
        return $this->uploadDestination;
    }

    public function deleteOldPicture(string $oldFile): void
    {
        $filelist = glob($this->getUploadDestination() . '*');
        foreach ($filelist as $file) {
            if ($file === $oldFile) {
                unlink($file);
            }
        }
    }
}
