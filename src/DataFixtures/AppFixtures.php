<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface, FixtureInterface
{
    
    const VIDEOS_LINKS = ["SQyTWk7OxSI", "YFRl91m6WS8", "uQgslXubZ4o","qsd8uaex-Is","gbHU6J6PRRw","GBknUJXw5qs","cVKamPWu_Sc","czpV-FOBHY4","bcSZDS65eGs","-kOsKKsJ_SE"];

    const CATEGORIES = ["grab","rotation","flip","rotation désaxée","slide","one foot","old school",];

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function load(ObjectManager $manager)
    {
        $author = new User();
        $author->setName('author')
                ->setEmail('author@test.com')
                ->setPassword('authortest')
                ->setActive(true);

        /** Add categories */
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $categoriesCollection[] = $category;
        }

        for ($i = 1; $i<20;$i++) {
            $trick = new Trick();
            $trick
            ->setName('Trick n° '.$i)
            ->setDescription('Description du Trick n° '.$i)
            ->setAuthor($author);
            
            /** Add img */
            for ($j = 0; $j < rand(1, 3); $j++) {
                $img = new Media();
                $img
                ->setType('img')
                ->setUrl('https://picsum.photos/150/150?random='.rand(1, 60));
                $trick->addMedia($img);
            }

            /** Add video */
            for ($k=0; $k < rand(0, 3); $k++) {
                $video = new Media();
                $video
                ->setType('video')
                ->setUrl("https://www.youtube.com/embed/" . self::VIDEOS_LINKS[array_rand(self::VIDEOS_LINKS, 1)]);
                $trick->addMedia($video);
            }
            
            /** Add Category */
            $TrickCategory = $categoriesCollection[array_rand($categoriesCollection, 1)];
            $trick->setCategory($TrickCategory);

            /** Add Comment */
            for ($l=0; $l < rand(0, 25); $l++) {
                $comment = new Comment();
                $comment->setContent('Contenu du commentaire n°'.$l.' au sujet du trick n°'.$i.' : '.$trick->getName().'.')
                ->setAuthor($author);
                $trick->addComment($comment);
            }

            $manager->persist($trick);
        }

        $manager->flush();
    }
}
