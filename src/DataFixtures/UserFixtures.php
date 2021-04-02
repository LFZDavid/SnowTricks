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
        return ['dev'];
    }

    public function load(ObjectManager $manager)
    {
        
        $user = new User();
        $i = rand(0,999);
        $user
            ->setEmail('user-'.$i.'@test.com')
            ->setPassword('user'.$i.'password')
            ->setName('User '.$i)
            ->setActive(true);
            $manager->persist($user);

        $manager->flush();
    }
}
