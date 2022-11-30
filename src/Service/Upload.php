<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Upload
{
    public const TEMP_PATH = 'files/temp/';
    public const PROFILE_PATH = 'files/profile/';
    public const CONVERT_PATH = 'files/pictures/';

    public function __construct(
        private string $uploadDestination,
        private SluggerInterface $slugger,
        private UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->uploadDestination = $uploadDestination;
        $this->slugger = $slugger;
    }

    public function imageUpload(UploadedFile $uploadedFile): string
    {
        $originaleFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originaleFileName);
        $newFileName = $safeFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $extension = explode('.', $originaleFileName);
        $extension = strtolower(end($extension));
        //send image to temp folder
        $uploadedFile->move(
            self::TEMP_PATH,
            $newFileName
        );
        //convert to png
        $convert = imagecreatefrompng(self::TEMP_PATH . $newFileName);
        imagewebp($convert, str_replace('png', 'webp', self::CONVERT_PATH . $newFileName));
        return $newFileName;
    }

    private function profilePictureSave(UploadedFile $uploadedFile): string
    {
        $originaleFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originaleFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move(
            self::PROFILE_PATH,
            $newFileName
        );
        return $newFileName;
    }

    public function uploadProfilePicture(FormInterface $form, User $user): void
    {
        $uploadedFile = $form->get('name')->getData();
        $oldProfilePicture = $user->getProfilePicture();
        $uploadFileName = $this->profilePictureSave($uploadedFile);
        $user->setProfilePicture(self::PROFILE_PATH . $uploadFileName);
        $this->userRepository->save($user, true);
        $this->deleteOldPicture($oldProfilePicture, self::PROFILE_PATH);
    }

    public function deleteOldPicture(?string $oldFile, string $filesFolder): void
    {
        $filelist = glob($filesFolder . '*');
        foreach ($filelist as $file) {
            if ($file === $oldFile) {
                unlink($file);
            }
        }
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
}
