<?php

namespace App\Tests;

use DateTime;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickTest extends WebTestCase
{

    public function testFindTrickFixture()
    {
        $trickName = 'Trick fixtures test';
        $trickSlug = 'trick-fixtures-test';

        $client = static::createClient();
        $crawler = $client->request('GET', '/trick/'.$trickSlug);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $trickName);
        
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
        $this->assertSelectorExists('.delete-trick-btn');
        
        /**Click on delete btn */
        $deleteBtn = $crawler->selectButton('Delete Trick');
        $form = $deleteBtn->form();

        /**set slug for route arg */
        $slugger = new AsciiSlugger();
        $slug = (string) $slugger->slug((string) $trickName)->lower();

        $client->submit($form, [], ["slug" => $slug]);
        
        //** Check redirection to home */
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h2.homepage-band-text');

        //** Check if trick is deleted */
        $crawler = $client->request('GET', $trickUri);
        $this->assertResponseStatusCodeSame(404);
            
    }

    // public function testDisplayCommentList()
    // {
    //     $client = static::createClient();
    //     /**Get create form */
    //     $crawler = $client->request('GET', '/');
    //     $trickUri = $crawler->filter('.trick-link')->attr('href');
    //     $crawler = $client->request('GET', $trickUri);
    //     // $this->assertResponseIsSuccessful();
    //     // $this->assertSelectorExists('h1.trick-title');

    //     // $this->assertSelectorExists('div.comments-block');
    // }

}