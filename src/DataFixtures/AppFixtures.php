<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Media;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i<20;$i++) {
            $trick = new Trick();
            $trick
            ->setName('Trick n° '.$i)
            ->setDescription('Description du Trick n° '.$i)
            ->setCreatedAt(new DateTime());
            $manager->persist($trick);

            $media = new Media();
            $media
            ->setUrl('https://picsum.photos/150/150?random='.$i)
            ->setTrick($trick);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
