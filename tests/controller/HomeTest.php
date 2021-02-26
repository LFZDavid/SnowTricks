<?php

namespace App\Tests;

use App\Controller\HomeController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{
    public function testTrickList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();
        
        $this->assertSelectorTextContains('h1', 'Liste des tricks');
        $this->assertCount(HomeController::DEFAULT_PAGINATE, $crawler->filter('h5.trick-name'));
    }
    
    public function testTrickListPage2():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/30');
        $this->assertResponseIsSuccessful();
        $count = count($crawler->filter('h5.trick-name'));
        $this->assertGreaterThan(
            HomeController::DEFAULT_PAGINATE, 
            $count
        );
    }

}
