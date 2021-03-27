<?php

namespace App\Tests\Service;

use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FileUploaderTest extends TestCase
{

    private $service;
    private $publicDirectory;
    private $imgRelativeDirectory;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->publicDirectory = 'tests/public';
        $this->imgRelativeDirectory = '/uploads/';
        $this->service = new FileUploader($this->publicDirectory, '/uploads', new AsciiSlugger());
        copy('tests/public/backup_files/img-test.jpg', 'tests/public/files_to_upload/img-test.jpg');
    }

    public function testUploadFile()
    {
        $file = new UploadedFile('tests/public/files_to_upload/img-test.jpg','img-test.jpg', null, null, true);
        $uploaded_files = $this->service->upload($file);
        $this->assertFileExists($this->publicDirectory.$uploaded_files);
        unlink($this->publicDirectory.$uploaded_files);

    }

}
