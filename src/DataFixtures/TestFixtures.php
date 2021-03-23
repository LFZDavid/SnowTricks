<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface, FixtureInterface
{
    private array $videos_link = [
        "https://www.youtube.com/embed/SQyTWk7OxSI",
        "https://www.youtube.com/embed/YFRl91m6WS8",
        "https://www.youtube.com/embed/uQgslXubZ4o",
        "https://www.youtube.com/embed/qsd8uaex-Is",
        "https://www.youtube.com/embed/gbHU6J6PRRw",
        "https://www.youtube.com/embed/GBknUJXw5qs",
        "https://www.youtube.com/embed/cVKamPWu_Sc",
        "https://www.youtube.com/embed/czpV-FOBHY4",
        "https://www.youtube.com/embed/bcSZDS65eGs",
        "https://www.youtube.com/embed/-kOsKKsJ_SE",
    ];

    private array $categories = [

        "grab",
        "rotation",
        "flip",
        "rotation désaxée",
        "slide",
        "one foot",
        "old school"
    ];

    private array $tricks = [
        "find",
        "show",
        "edit",
        "delete",
        "to-comment",
        "has-no-comment",
        "has-one-comment",
        "has-eleven-comments",
    ];

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {

        /** Add categories */
        foreach ($this->categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $categoriesCollection[] = $category;
        }

        foreach ($this->tricks as $trickType) {
            $trick = new Trick();
            $trick->setName($trickType)
                ->setDescription('Description du trick '.$trickType);

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
                ->setUrl($this->videos_link[rand(0, 9)]);
                $trick->addMedia($video);
            }

            /** Add Category */
            $TrickCategory = $categoriesCollection[array_rand($categoriesCollection, 1)];
            $trick->setCategory($TrickCategory);

            /** Add Comment */
            $nb_comments = rand(1, 20);

            if ($trickType == 'has-no-comment') {
                $nb_comments = 0;
            } elseif ($trickType == 'has-one-comment') {
                $nb_comments = 1;
            } elseif ($trickType == 'has-eleven-comments') {
                $nb_comments = 11;
            }

            for ($l=0; $l < $nb_comments; $l++) {
                $comment = new Comment();
                $comment->setContent('Contenu du commentaire n°'.($l+1).' au sujet du trick '.$trick->getName().'.');
                $trick->addComment($comment);
            }

            $manager->persist($trick);
        }

        $manager->flush();
    }
}
