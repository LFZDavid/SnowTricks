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
        return ['dev','test'];
    }

    public function load(ObjectManager $manager)
    {
        
        $user = new User();
        $user
            ->setEmail('user@test.com')
            ->setPassword('userpassword')
            ->setName('UserTest')
            ->setActive(true);
            $manager->persist($user);

        $manager->flush();
    }
}
