<?php

namespace App\EventListener;

use DateTime;
use App\Entity\Trick;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CreateTrickListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Trick $trick): void
    {
        if (!$trick->getName()) {
            return;
        }
        $slug = $this->slugger->slug($trick->getName())->lower();
        $trick->setSlug($slug);
    }
}
