<?php

namespace App\Service;

use App\Service\Upload;

class Convert
{
    public function convert(string $fileName): void
    {
        // dd($fileName);
        //break file in two
        $extension = explode('.', $fileName);
        // dd($extension);
        $extension = strtolower(end($extension));
        // dd($fileName);
    }
    public function convertPngToWebp(string $fileName): void
    {
        $convertPngToWebp = imagecreatefrompng(Upload::TEMP_PATH . $fileName);
        imagewebp($convertPngToWebp, str_replace('png', 'webp', Upload::CONVERT_PATH . $fileName));
    }
    public function convertJpgToWebp(string $fileName): void
    {
        // $fileToConvert = $fileName->imageUpload($getName);
        // dd($fileName);
        $convertJpgToWebp = imagecreatefromjpeg(Upload::TEMP_PATH . $fileName);
        imagewebp($convertJpgToWebp, str_replace('jpg', 'webp', Upload::CONVERT_PATH . $fileName));
    }
    public function convertWebpToPng(string $fileName): void
    {
        $convertWebpToPng = imagecreatefromwebp(Upload::TEMP_PATH . $fileName);
        imagepng($convertWebpToPng, str_replace('webp', 'png', Upload::CONVERT_PATH . $fileName));
    }
    public function convertWebpToJpg(string $fileName): void
    {
        $convertWebpToJpg = imagecreatefromwebp(Upload::TEMP_PATH . $fileName);
        imagepng($convertWebpToJpg, str_replace('webp', 'jpg', Upload::CONVERT_PATH . $fileName));
    }
}
