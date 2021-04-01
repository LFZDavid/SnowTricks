<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {
        
        for ($i=0; $i < 10; $i++) { 

            $user = new User();
            $user
                ->setEmail('user-'.$i.'@test.com')
                ->setPassword('user'.$i.'password')
                ->setName('User '.$i);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
