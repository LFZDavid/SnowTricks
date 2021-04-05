<?php

namespace App\Service;

use App\Entity\Avatar;
use App\Service\FileUploader;
use Symfony\Component\String\Slugger\SluggerInterface;

class AvatarFileUploader extends FileUploader
{
    public function deleteAvatarFile(Avatar $avatar)
    {
        $file = $this->publicDirectory . $avatar->getUrl();
        if(file_exists($file)) {
            unlink($file);
        }
    }

}