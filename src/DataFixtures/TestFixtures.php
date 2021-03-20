<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface, FixtureInterface
{
    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {
        $trickName = 'Trick fixtures test';
        $testTrick = new Trick();
        $testTrick->setName($trickName)
            ->setDescription('Description du Trick fixtures test');
        $manager->persist($testTrick);


        $manager->flush();
    }

}
