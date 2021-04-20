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
        
        $users = [
                "valid",
                "notvalid",
                "tovalid",
                "todelete",
        ];

        foreach ($users as $item) {
            $user = new User();
            $user
                ->setEmail($item.'@test.com')
                ->setPassword($item)
                ->setName($item)
                ->setActive($item !== 'notvalid' ? true : false);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
