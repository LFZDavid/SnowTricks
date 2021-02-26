<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickTest extends WebTestCase
{
    public function testTrickList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();
        $this->assertCount(15, $crawler->filter('h2.trick-name'));
    }
    
    public function testTitle():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des tricks');
    }
}
