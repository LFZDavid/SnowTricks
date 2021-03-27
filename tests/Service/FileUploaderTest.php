<?php

namespace App\Tests\Service;

use App\Entity\Media;
use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FileUploaderTest extends TestCase
{

    private $service;
    private $publicDirectory;
    private $imgRelativeDirectory;
    private $backupFile;
    private $testFilePath;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->publicDirectory = 'tests/public';
        $this->imgRelativeDirectory = '/uploads';
        $this->backupFile = '/backup_files/img-test.jpg';
        $this->testFilePath = '/files_to_upload/img-test.jpg';

        $this->service = new FileUploader(
            $this->publicDirectory, 
            $this->imgRelativeDirectory, 
            new AsciiSlugger()
        );

        copy($this->publicDirectory.$this->backupFile, $this->publicDirectory.$this->testFilePath);
    }

    public function testUploadFile(): void
    {
        $file = new UploadedFile(
            $this->publicDirectory.$this->testFilePath,
            'img-test.jpg', 
            null, 
            null, 
            true
        );

        $uploaded_files = $this->service->upload($file);
        $this->assertFileExists($this->publicDirectory.$uploaded_files);
        unlink($this->publicDirectory.$uploaded_files);

    }

    public function testDeleteFile(): void
    {
        $media = new Media();
        $media->setUrl($this->testFilePath);
        $this->service->deleteFile($media);
        $this->assertFileDoesNotExist($media->getUrl());
    }

}
