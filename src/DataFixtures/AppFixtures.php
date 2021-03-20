<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Media;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    private $videos_link = [
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

    public function load(ObjectManager $manager)
    {
        $slugger = new AsciiSlugger();

        for($i = 1; $i<20;$i++) {
            $trick = new Trick();
            $trick
            ->setName('Trick n° '.$i)
            ->setDescription('Description du Trick n° '.$i);
            $slug = (string) $slugger->slug((string) $trick->getName())->lower();
            $trick->setSlug($slug);
            
            /** Add img */
            for($j = 0; $j < rand(1,3); $j++) {
                $img = new Media();
                $img
                ->setType('img')
                ->setUrl('https://picsum.photos/150/150?random='.rand(1,60));
                $trick->addMedia($img);
            }

            /** Add video */
            for ($k=0; $k < rand(0,3); $k++) { 
                $video = new Media();
                $video
                ->setType('video')
                ->setUrl($this->videos_link[rand(0,9)]);
                $trick->addMedia($video);
            }
            
            $manager->persist($trick);
        }

        $manager->flush();
    }

}
