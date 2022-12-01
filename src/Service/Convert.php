<?php

namespace App\Service;

use App\Service\Upload;

class Convert
{
    public function getExtension(string $fileName): string
    {
        $extension = explode('.', $fileName);
        return strtolower(end($extension));
    }
    public function stripPath(string $filename): string
    {
        return str_replace('files/pictures/', '', $filename);
    }
    public function convertPngToWebp(string $fileName): string
    {
        $convertPngToWebp = imagecreatefrompng(Upload::TEMP_PATH . $fileName);
        $extension = $this->getExtension($fileName);
        $convertedPicture = str_replace($extension, 'webp', Upload::CONVERT_PATH . $fileName);
        imagewebp($convertPngToWebp, str_replace($extension, 'webp', Upload::CONVERT_PATH . $fileName));
        return $this->stripPath($convertedPicture);
    }
    public function convertJpgToWebp(string $fileName): string
    {
        // $fileToConvert = $fileName->imageUpload($getName);
        // dd($fileName);
        $convertJpgToWebp = imagecreatefromjpeg(Upload::TEMP_PATH . $fileName);
        $extension = $this->getExtension($fileName);
        $convertedPicture = str_replace($extension, 'webp', Upload::CONVERT_PATH . $fileName);
        imagewebp($convertJpgToWebp, str_replace($extension, 'webp', Upload::CONVERT_PATH . $fileName));
        return $this->stripPath($convertedPicture);
    }
    public function convertPngToJpg(string $fileName): string
    {
        $convertPngToJpg = imagecreatefrompng(Upload::TEMP_PATH . $fileName);
        $extension = $this->getExtension($fileName);
        $convertedPicture = str_replace($extension, 'jpg', Upload::CONVERT_PATH . $fileName);
        imagejpeg($convertPngToJpg, str_replace($extension, 'jpg', Upload::CONVERT_PATH . $fileName));
        return $this->stripPath($convertedPicture);
    }
    public function convertWebpToJpg(string $fileName): string
    {
        $convertWebpToJpg = imagecreatefromwebp(Upload::TEMP_PATH . $fileName);
        $extension = $this->getExtension($fileName);
        $convertedPicture = str_replace($extension, 'jpg', Upload::CONVERT_PATH . $fileName);
        imagejpeg($convertWebpToJpg, str_replace($extension, 'jpg', Upload::CONVERT_PATH . $fileName));
        return $this->stripPath($convertedPicture);
    }
    public function convertWebpToPng(string $fileName): string
    {
        $convertWebpToPng = imagecreatefromwebp(Upload::TEMP_PATH . $fileName);
        $extension = $this->getExtension($fileName);
        $convertedPicture = str_replace($extension, 'png', Upload::CONVERT_PATH . $fileName);
        imagepng($convertWebpToPng, str_replace($extension, 'png', Upload::CONVERT_PATH . $fileName));
        return $this->stripPath($convertedPicture);
    }
    public function convertJpgToPng(string $fileName): string
    {
        $convertJpgToPng = imagecreatefromjpeg(Upload::TEMP_PATH . $fileName);
        $extension = $this->getExtension($fileName);
        $convertedPicture = str_replace($extension, 'png', Upload::CONVERT_PATH . $fileName);
        imagepng($convertJpgToPng, str_replace($extension, 'png', Upload::CONVERT_PATH . $fileName));
        return $this->stripPath($convertedPicture);
    }
    public function convertPngFromDropdown(int $dropdown, string $uploadFileName): string
    {
        $convertedFile = '';
        if (($dropdown !== 1 && $dropdown !== 3)) {
            ($dropdown === 2)
                ?  $convertedFile = $this->convertPngToWebp($uploadFileName)
                :  $convertedFile = $this->convertPngToJpg($uploadFileName);
        }
        return $convertedFile;
    }
    public function convertJpgFromDropdown(int $dropdown, string $uploadFileName): string
    {
        $convertedFile = '';
        if ($dropdown !== 1 && $dropdown !== 4) {
            ($dropdown === 2)
                ? $convertedFile = $this->convertJpgToWebp($uploadFileName)
                : $convertedFile = $this->convertJpgToPng($uploadFileName);
        }
        return $convertedFile;
    }
    public function convertWebpFromDropdown(int $dropdown, string $uploadFileName): string
    {
        $convertedFile = '';
        if ($dropdown !== 1 && $dropdown !== 2) {
            ($dropdown === 3)
                ? $convertedFile = $this->convertWebpToPng($uploadFileName)
                : $convertedFile = $this->convertWebpToJpg($uploadFileName);
        }
        return $convertedFile;
    }
}
