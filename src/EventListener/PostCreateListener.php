<?php

use App\Entity\Post;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\Filesystem\Filesystem;

class PostCreateListener
{
    private ?Filesystem $fs = null;

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }

    public function prePersistPost(Post $user, PrePersistEventArgs $event)
    {
        $this->fs->appendToFile('log.txt', 'Une nouvelle connexion !');
        //dd($event);
    }
}
