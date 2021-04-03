<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickTest extends WebTestCase
{
    use FixturesTrait;
    private $client;
    private $userRepository;
    private $userTest;


    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
        $this->userTest = $this->userRepository->findOneByEmail('valid@test.com');
    }

    public function testHomepageHaveLessThanSixteenTrick()
    {
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
        $this->client->loginUser($this->userTest);
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
        $this->client->loginUser($this->userTest);
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
        $this->client->loginUser($this->userTest);

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
        $this->client->loginUser($this->userTest);
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
        $this->client->loginUser($this->userTest);
        $crawler = $this->client->request('GET', '/trick/to-comment');
        $buttonCrawlerNode = $crawler->filter('form');
        $form = $buttonCrawlerNode->form();
        $this->client->submit($form);
        $this->assertSelectorExists('span.form-error-message');
    }

    public function testSubmitValidComment()
    {
        $this->client->loginUser($this->userTest);
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

    
    
}
