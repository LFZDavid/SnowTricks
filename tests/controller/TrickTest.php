<?php

namespace App\Tests;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
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
        $this->assertLessThan(16, count($crawler->filter('h5.trick-title')));
    }
    
    public function testHomepagePagination()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/100');
        $this->assertResponseIsSuccessful();
    }

    public function testUnexistsTrick()
    {
        $client = static::createClient();
        $wrongSlug = 'this-is-a-wrong-slug';
        $crawler = $client->request('GET', '/trick/'.$wrongSlug);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testFindTrickInHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/100');
        $find = $crawler->filter('h5.trick-name:contains("find")');
        $this->assertEquals(1, count($find));
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

    public function testIncompleteCreateTrickFormSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/trick/create');
        /**Get form */
        $buttonCrawlerNode = $crawler->filter('form');
        /**Fill and submit form */
        $form = $buttonCrawlerNode->form();
        $client->submit($form);
        $this->assertSelectorExists('span.form-error-message');
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
        $this->assertSelectorExists('section#trick-list');
        
        //** Check if trick is deleted */
        $crawler = $client->request('GET', '/trick/'.$trickSlug);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testTrickWithOneComment()
    {
        $client = static::createClient();

        $slugger = new AsciiSlugger();
        $trickName = 'has-one-comment';
        $slug = (string) $slugger->slug((string) $trickName)->lower();
        
        $crawler = $client->request('GET', '/trick/'.$slug);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.comment');
        $this->assertSelectorNotExists('a.load-more-btn');
    }

    public function testTrickCommentsDefaultPaginate()
    {
        $client = static::createClient();

        $slugger = new AsciiSlugger();
        $trickName = 'has-eleven-comments';
        $slug = (string) $slugger->slug((string) $trickName)->lower();
        
        $crawler = $client->request('GET', '/trick/'.$slug);
        $this->assertResponseIsSuccessful();
        $this->assertLessThan(11, count($crawler->filter('div.comment')));
    }

    public function testTrickCommentsPaginateLoadMore()
    {
        $client = static::createClient();

        $slugger = new AsciiSlugger();
        $trickName = 'has-eleven-comments';
        $slug = (string) $slugger->slug((string) $trickName)->lower();
        
        $crawler = $client->request('GET', '/trick/'.$slug);
        $link = $crawler->filter('a.load-more-btn')->first()->link();
        $crawler = $client->click($link);

        $this->assertGreaterThan(10, count($crawler->filter('div.comment')));
    }
}
