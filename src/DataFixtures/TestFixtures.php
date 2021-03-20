<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\Media;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class TestFixtures extends Fixture implements FixtureGroupInterface
{

    public static function getGroups(): array
    {
        return ['test'];
    }
    
    public function load(ObjectManager $manager)
    {
        $slugger = new AsciiSlugger();
        $trick = new Trick();
        $trick
        ->setName('Trick fixtures test')
        ->setDescription('Description du Trick fixtures test');
        $manager->persist($trick);
        $manager->flush();
    }

}
