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
        /**Create trick test */
        $datetime = new DateTime();
        $client = static::createClient();
        
        
        $crawler = $client->request('GET', '/trick/create');
        
        $buttonCrawlerNode = $crawler->filter('form');

        $trickName = 'trick Test'.rand(100000,999999);
        $trickContent = 'Contenu du trick test...'.$datetime->format('Y-m-d H:i');

        $form = $buttonCrawlerNode->form();
        $form['trick[name]'] = $trickName;
        $form['trick[description]'] = $trickContent;
        $form['trick[category]'] = '1';

        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        
        /**Check if trick is added in homepage */
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5.trick-name', $trickName);
    }

}