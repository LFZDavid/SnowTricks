<?php

namespace App\Tests;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickTest extends WebTestCase
{
    public function testTrickShow(): void
    {
        $client = static::createClient();
        $fakeTrick = new Trick();
        $fakeTrick->setSlug('trick-n-19');
        $crawler = $client->request(
            'GET',
            "/trick/".$fakeTrick->getSlug()
        );
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1','Trick n° 19');

    }

    public function testGetForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',"/trick/create");
        $this->assertResponseIsSuccessful();
    }
}