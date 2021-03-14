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

    public function testCreateAndDeleteTrick(): void
    {
        //**Create trick test */
        $datetime = new DateTime();
        $client = static::createClient();
        /**Get create form */
        $crawler = $client->request('GET', '/trick/create');
        /**Get form */
        $buttonCrawlerNode = $crawler->filter('form');
        /**Fill and submit form */
        $form = $buttonCrawlerNode->form();
        $trickName = 'trick Test'.rand(100000,999999);
        $form['trick[name]'] = $trickName;
        $form['trick[description]'] = 'Contenu du trick test...'.$datetime->format('Y-m-d H:i');
        $form['trick[category]'] = '';
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        
        //**Check if trick is added in homepage */
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5.trick-name', $trickName);
        
        //** Delete trick */
        /**get trick page uri */
        $trickUri = $crawler->selectLink($trickName)->link()->getUri();
        /**Go to trick page */
        $crawler = $client->request('GET', $trickUri);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $trickName);
        
        /**Go to edit form */
        $crawler = $client->request('GET', $trickUri.'/edit');
        $this->assertResponseIsSuccessful();
        
        /**Click on delete btn */
        $this->assertSelectorExists('.btn-danger .delete-trick-btn');
        
        //** Check if trick is deleted */
        // Follow redirection to home
        // Assert Trick is delete (404 on url)

    }

}