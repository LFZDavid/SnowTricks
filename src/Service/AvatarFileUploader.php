<?php

namespace App\Service;

use App\Entity\Avatar;
use App\Service\FileUploader;
use Symfony\Component\String\Slugger\SluggerInterface;

class AvatarFileUploader extends FileUploader
{
    const DEFAULT_IMG_URL = '/img/users/default.png';

    public function deleteAvatarFile(Avatar $avatar)
    {
        $file = $this->publicDirectory . $avatar->getUrl();
        if(file_exists($file) && $avatar->getUrl() != self::DEFAULT_IMG_URL) {
            unlink($file);
        }
    }

}