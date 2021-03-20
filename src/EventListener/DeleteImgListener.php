<?php

namespace App\EventListener;

use App\Entity\Media;
use App\Service\FileUploader;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DeleteImgListener
{
    
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function postRemove(Media $media, LifecycleEventArgs $event): void
    {
        $this->fileUploader->deleteFile($media);
    }
}