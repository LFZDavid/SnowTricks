<?php

namespace App\Tests;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickTest extends WebTestCase
{
    use FixturesTrait;
    private $client;


    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testHomepageHaveLessThanSixteenTrick()
    {
        $this->loadFixtures([
            'App\DataFixtures\TestFixtures'
        ], true);

        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertLessThan(16, count($crawler->filter('h5.trick-title')));
    }
    
    public function testHomepagePagination()
    {
        $crawler = $this->client->request('GET', '/100');
        $this->assertResponseIsSuccessful();
    }

    public function testUnexistsTrick()
    {
        $wrongSlug = 'this-is-a-wrong-slug';
        $crawler = $this->client->request('GET', '/trick/'.$wrongSlug);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testFindTrickInHomepage()
    {
        $crawler = $this->client->request('GET', '/100');
        $find = $crawler->filter('h5.trick-name:contains("find")');
        $this->assertEquals(1, count($find));
    }

    public function testTrickShow()
    {
        $crawler = $this->client->request('GET', '/trick/show');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1.trick-title', 'show');
    }

    public function testCreateTrick()
    {
        $crawler = $this->client->request('GET', '/trick/create');
        /**Get form */
        $buttonCrawlerNode = $crawler->filter('form');
        /**Fill and submit form */
        $form = $buttonCrawlerNode->form();
        $trickName = 'trick a créer';
        $form['trick[name]'] = $trickName;
        $form['trick[description]'] = 'Contenu du trick créé...';
        $form['trick[category]'] = '';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        
        //**Check if trick is added in homepage */
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5.trick-name', $trickName);
    }

    public function testIncompleteCreateTrickFormSubmit()
    {
        $crawler = $this->client->request('GET', '/trick/create');
        /**Get form */
        $buttonCrawlerNode = $crawler->filter('form');
        /**Fill and submit form */
        $form = $buttonCrawlerNode->form();
        $this->client->submit($form);
        $this->assertSelectorExists('span.form-error-message');
    }

    public function testEditTrick()
    {
        $trickSlug = 'edit';
        $newTrickName = 'edit modifié !';

        $crawler = $this->client->request('GET', '/trick/'.$trickSlug.'/edit');
        /**Fill and submit form */
        $buttonCrawlerNode = $crawler->filter('form');
        $form = $buttonCrawlerNode->form();
        $form['trick[name]'] = $newTrickName;
        $form['trick[description]'] = 'Contenu du trick modifié...';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        /**Check if modifications are saved */
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5.trick-name', $newTrickName);
    }

    public function testDeleteTrick()
    {
        $trickSlug = 'delete';
        $crawler = $this->client->request('GET', '/trick/'.$trickSlug.'/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.delete-trick-btn');
        $deleteBtn = $crawler->selectButton('Delete Trick');
        $form = $deleteBtn->form();
        $this->client->submit($form, [], ["slug" => $trickSlug]);
        //** Check redirection to home */
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('section#trick-list');
        //** Check if trick is deleted */
        $crawler = $this->client->request('GET', '/trick/'.$trickSlug);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testTrickWithOneComment()
    {
        $this->client->request('GET', '/trick/has-one-comment');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.comment');
        $this->assertSelectorNotExists('a.load-more-btn');
    }

    public function testTrickCommentsDefaultPaginate()
    {
        $crawler = $this->client->request('GET', '/trick/has-eleven-comments');
        $this->assertResponseIsSuccessful();
        $this->assertLessThan(11, count($crawler->filter('div.comment')));
    }

    public function testTrickCommentsPaginateLoadMore()
    {
        $crawler = $this->client->request('GET', '/trick/has-eleven-comments');
        $link = $crawler->filter('a.load-more-btn')->first()->link();
        $crawler = $this->client->click($link);
        $this->assertGreaterThan(10, count($crawler->filter('div.comment')));
    }

    public function testSubmitEmptyComment()
    {
        $crawler = $this->client->request('GET', '/trick/to-comment');
        $buttonCrawlerNode = $crawler->filter('form');
        $form = $buttonCrawlerNode->form();
        $this->client->submit($form);
        $this->assertSelectorExists('span.form-error-message');
    }

    public function testSubmitValidComment()
    {
        $commentContent = 'Contenu du commentaire';
        $crawler = $this->client->request('GET', '/trick/to-comment');
        $buttonCrawlerNode = $crawler->filter('form');
        $form = $buttonCrawlerNode->form();
        $form['comment[content]'] = $commentContent;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertSelectorNotExists('span.form-error-message');
        $this->assertSelectorTextContains('.comment-content', $commentContent);
    }

    public function testGetSignUpForm()
    {
        $crawler = $this->client->request('GET', '/user/signup');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    
    public function testSubmitEmptySignUpForm()
    {
        $this->client->request('GET', '/user/signup');
        $crawler = $this->client->submitForm('Enregistrer', [
            "user[password]" => 'azeaze'
        ]);
        $this->assertSelectorExists('span.form-error-message');
    }

    public function testCreateUser()
    {
        $this->client->request('GET', '/user/signup');
        $crawler = $this->client->submitForm('Enregistrer', [
            "user[name]" => 'User Test',
            "user[email]" => 'test@test.com',
            "user[password]" => 'userpassword',
            "user[confirm_password]" => 'userpassword',
        ]);
        $this->assertSelectorNotExists('span.form-error-message');
        $this->assertResponseRedirects();
    }
    
}
