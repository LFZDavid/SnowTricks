<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $targetDirectory;
    private SluggerInterface $slugger;
    private string $targetDirectoryRelativePath;

    public function __construct(string $targetDirectory, string $targetDirectoryRelativePath, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->targetDirectoryRelativePath = $targetDirectoryRelativePath;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->targetDirectory, $fileName);
        } catch (FileException $e) {

            return $e->getMessage();
            //todo : handle exception and display message in front
        }

        return $this->targetDirectoryRelativePath.'/'.$fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

}