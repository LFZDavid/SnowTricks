<?php

namespace App\Service;

use App\Entity\Media;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private string $publicDirectory;
    private SluggerInterface $slugger;
    private string $imgRelativeDirectory;

    public function __construct(string $publicDirectory, string $imgRelativeDirectory, SluggerInterface $slugger)
    {
        $this->publicDirectory = $publicDirectory;
        $this->imgRelativeDirectory = $imgRelativeDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $filepath = $this->publicDirectory . $this->imgRelativeDirectory;

        try {
            $file->move($filepath, $fileName);
        } catch (FileException $e) {

            return $e->getMessage();
            //todo : handle exception and display message in front
        }

        return $this->imgRelativeDirectory. '/' . $fileName;
    }

    public function deleteFile(Media $media)
    {
        $file = $this->publicDirectory . $media->getUrl();
        unlink($file);
    }

}