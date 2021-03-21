<?php

namespace App\Tests;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickTest extends WebTestCase
{
    use FixturesTrait;

    public function testHomepageHaveLessThanSixteenTrick()
    {
        $client = static::createClient();

        $this->loadFixtures([
            'App\DataFixtures\TestFixtures'
        ], true);

        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertLessThan(16, 'h5.trick-title');
    }
    
    public function testHomepagePagination()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/100');
        $this->assertResponseIsSuccessful();
    }

    public function testFindTrickInHomepage()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/100');
        $this->assertSelectorTextContains('h5.trick-name', 'find');
    }

    public function testTrickShow()
    {
        $client = static::createClient();

        $slugger = new AsciiSlugger();
        $trickName = 'show';
        $slug = (string) $slugger->slug((string) $trickName)->lower();
        
        $crawler = $client->request('GET', '/trick/'.$slug);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1.trick-title', $trickName);
    }

    public function testCreateTrick()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/trick/create');
        /**Get form */
        $buttonCrawlerNode = $crawler->filter('form');
        /**Fill and submit form */
        $form = $buttonCrawlerNode->form();
        $trickName = 'trick a créer';
        $form['trick[name]'] = $trickName;
        $form['trick[description]'] = 'Contenu du trick créé...';
        $form['trick[category]'] = '';
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        
        //**Check if trick is added in homepage */
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5.trick-name', $trickName);
    }

    public function testEditTrick()
    {
        $client = static::createClient();

        $slugger = new AsciiSlugger();
        $trickName = 'edit';
        $trickSlug = (string) $slugger->slug((string) $trickName)->lower();

        $crawler = $client->request('GET', '/trick/'.$trickSlug.'/edit');

        /**Fill and submit form */
        $buttonCrawlerNode = $crawler->filter('form');
        $form = $buttonCrawlerNode->form();
        $trickName .= ' modifié !';
        $form['trick[name]'] = $trickName;
        $form['trick[description]'] = 'Contenu du trick modifié...';
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        /**Check if modifications are saved */
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5.trick-name', $trickName);
    }

    public function testDeleteTrick()
    {
        $client = static::createClient();

        $slugger = new AsciiSlugger();
        $trickName = 'delete';
        $trickSlug = (string) $slugger->slug((string) $trickName)->lower();

        $crawler = $client->request('GET', '/trick/'.$trickSlug.'/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.delete-trick-btn');
        
        /**Click on delete btn */
        $deleteBtn = $crawler->selectButton('Delete Trick');
        $form = $deleteBtn->form();
        $client->submit($form, [], ["slug" => $trickSlug]);
        
        //** Check redirection to home */
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h2.homepage-band-text');
        
        //** Check if trick is deleted */
        $crawler = $client->request('GET', '/trick/'.$trickSlug);
        $this->assertResponseStatusCodeSame(404);
    }
}
