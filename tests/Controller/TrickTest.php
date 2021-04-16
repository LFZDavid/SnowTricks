<?php

namespace App\Tests;

use App\Repository\TrickRepository;
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
    private $trickRepository;


    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
        $this->trickRepository = static::$container->get(TrickRepository::class);
        $this->userTest = $this->userRepository->findOneByEmail('valid@test.com');
    }

    /** *** Test Tick Show *** */

    /**
     * Error
     * Check if error code is returned on trying to get a wrong trick page
     */
    public function testUnexistsTrick()
    {
        $wrongSlug = 'this-is-a-wrong-slug';
        $this->client->request('GET', '/trick/'.$wrongSlug);
        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * Success
     * Show trick and check if every infos is present in the page
     */
    public function testTrickShow()
    {
        $trick = $this->trickRepository->findOneByName('show');
        $this->client->request('GET', '/trick/'.$trick->getSlug());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1.trick-title', $trick->getName());
        $this->assertSelectorTextContains('div.trick-desc', $trick->getDescription());
        $this->assertSelectorTextContains('.category-badge', ucfirst($trick->getCategory()->getName()));
    }

    /** *** Test Create Trick *** */

    /**
     * Error
     * Can't access to create form if not logged
     */
    public function testCreateFormNotAccessibleForNotLoggedUser()
    {
        $this->client->request('GET', '/trick/create');
        $this->assertSelectorNotExists('form');
    }

    /**
     * Success
     * Check if create form is complete
     */
    public function testInputsInCreateTrickForm()
    {
        $this->client->loginUser($this->userTest);
        $this->client->request('GET', '/trick/create');
        $this->assertSelectorExists('#trick_name');
        $this->assertSelectorExists('#trick_description');
        $this->assertSelectorExists('#trick_category');
        $this->assertSelectorExists('.add_media_link');
    }

    /**
     * Error
     * Check if create a duplicate Trick return error
     */
    public function testCantAddDuplicateTrick()
    {
        $this->client->loginUser($this->userTest);
        $crawler = $this->client->request('GET', '/trick/create');
        /**Get form */
        $buttonCrawlerNode = $crawler->filter('form');
        /**Fill and submit form */
        $form = $buttonCrawlerNode->form();
        $trickName = 'find'; // Allready exist!
        $form['trick[name]'] = $trickName;
        $form['trick[description]'] = 'Contenu du trick créé...';
        $form['trick[category]'] = '';
        $this->client->submit($form);
        $this->assertSelectorTextContains('span.form-error-message','Ce nom est déjà pris !');
    }

    /**
     * Success
     * Check if submit complete create form works
     */
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

        /** Check success message */
        $this->assertSelectorExists('.alert-success');
        
        //**Check if trick is added in homepage */
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5.trick-name', $trickName);
    }

     /**
     * Error
     * Check if submit incomplete create form return errors
     */
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

    /** *** Test Edit Trick *** */

    /**
     * Error
     * Can't access to edit form if not logged
     */
    public function testEditFormNotAccessibleForNotLoggedUser()
    {
        $trickToEdit = $this->trickRepository->findOneByName('edit');
        $this->client->request('GET', '/trick/'.$trickToEdit->getSlug().'/edit');
        $this->assertSelectorNotExists('form');
    }

    /**
     * Success
     * Edit form fields are filled with trick datas
     */
    public function testEditFormFieldsAreFulfilled()
    {
        $trickToEdit = $this->trickRepository->findOneByName('edit');
        $this->client->loginUser($this->userTest);
        $this->client->request('GET', '/trick/'.$trickToEdit->getSlug().'/edit');

        $this->assertInputValueSame('trick[name]', $trickToEdit->getName());
        $this->assertSelectorTextContains('#trick_description', $trickToEdit->getDescription());
    }

    /**
     * Success
     * Check if submit complete edit form works
     */
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

        /** Check success message */
        $this->assertSelectorExists('.alert-success');

        /**Check if modifications are saved */
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5.trick-name', $newTrickName);
    }

    /**
     * Success
     * Check if delete trick works
     */
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
        /** Check success message */
        $this->assertSelectorExists('.alert-danger');
        //** Check if trick is deleted */
        $crawler = $this->client->request('GET', '/trick/'.$trickSlug);
        $this->assertResponseStatusCodeSame(404);
    }

    /** *** Comments *** */

    /**
     * Error
     * Can't access to comment form if not logged
     */
    public function testCommentFormIfNotLogged()
    {
        $trick = $this->trickRepository->findOneByName('has-no-comment');
        $this->client->request('GET', '/trick/'.$trick->getSlug());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorNotExists('.comment-form>form');
        $this->assertSelectorExists('.disclaimer-comment');
        $this->assertSelectorExists('.signup-link');
        $this->assertSelectorExists('.signin-link');

    }

    /**
     * Success
     * Check if trick's comment is present in the trick show page
     * Check pagination (less than 10 comments doesn't show "Load more..." button)
     */
    public function testTrickWithOneComment()
    {
        $this->client->request('GET', '/trick/has-one-comment');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.comment');
        $this->assertSelectorNotExists('a.load-more-btn');
    }

    /**
     * Success
     * Check pagination (more than 10 comments show "Load more..." button)
     */
    public function testTrickCommentsDefaultPaginate()
    {
        $crawler = $this->client->request('GET', '/trick/has-eleven-comments');
        $this->assertResponseIsSuccessful();
        $this->assertLessThan(11, count($crawler->filter('div.comment')));
    }

    /**
     * Success
     * Check pagination click on "Load more..." button display more comments
     */
    public function testTrickCommentsPaginateLoadMore()
    {
        $crawler = $this->client->request('GET', '/trick/has-eleven-comments');
        $link = $crawler->filter('a.load-more-btn')->first()->link();
        $crawler = $this->client->click($link);
        $this->assertGreaterThan(10, count($crawler->filter('div.comment')));
    }

    /**
     * Error
     * Can't submit empty comment
     */
    public function testSubmitEmptyComment()
    {
        $this->client->loginUser($this->userTest);
        $crawler = $this->client->request('GET', '/trick/to-comment');
        $buttonCrawlerNode = $crawler->filter('form');
        $form = $buttonCrawlerNode->form();
        $this->client->submit($form);
        $this->assertSelectorExists('span.form-error-message');
    }

    /**
     * Success
     * Comment form works
     */
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
        /** Check success message */
        $this->assertSelectorExists('.alert-success');
    }

    /**
     * Success
     * Created comment is present in show trick page
     */
    public function testFindCommentDataInCommentList()
    {
        $trick = $this->trickRepository->findOneByName('has-one-comment');
        $comment = $trick->getComments()[0];
        $this->client->request('GET', '/trick/has-one-comment');
        $this->assertSelectorTextContains('.comment-author', $comment->getAuthor()->getName());
        $this->assertSelectorTextContains('.comment-createdAt', $comment->getCreatedAt()->format('d/m/Y H:i'));
        $this->assertSelectorTextContains('.comment-content', $comment->getContent());

    }
    
}
