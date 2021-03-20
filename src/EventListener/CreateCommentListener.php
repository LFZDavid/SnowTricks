<?php

namespace App\EventListener;

use DateTime;
use App\Entity\Comment;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CreateCommentListener
{
    public function prePersist(Comment $comment): void
    {
        $comment->setCreatedAt(new DateTime());
    }
}