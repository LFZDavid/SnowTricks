<?php

namespace App\Tests;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use DateTime;
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

    public function testGetCreateForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',"/trick/create");
        $this->assertResponseIsSuccessful();
    }

    public function testGetEditForm(): void
    {
        $client = static::createClient();
        $fakeTrick = new Trick();
        $fakeTrick->setSlug('trick-n-19');
        $crawler = $client->request(
            'GET',
            "/trick/".$fakeTrick->getSlug()."/edit"
        );
        $this->assertResponseIsSuccessful();
        
    }

    public function testCreateTrick(): void
    {
        $datetime = new DateTime();
        $client = static::createClient();
        $client->request('POST', '/trick/create',[
            'name' => 'trick Test'.$datetime->format('Y-m-d H:i'),
            'description' => 'Contenu du trick test...'
        ]);
        
        $this->assertResponseIsSuccessful();
    }

}