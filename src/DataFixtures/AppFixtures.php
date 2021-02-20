<?php

namespace App\DataFixtures;

use App\Entity\Trick;
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
        }

        $manager->flush();
    }
}
