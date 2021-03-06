<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
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
        $this->assertSelectorExists('a.trick-link>h5.trick-name');
    }

    public function testNotDisplayEditTrickBtnsIfNotLogged()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertSelectorNotExists('.trick-btns');
    }

    /**
     * Assert edit and delete btns are present on homepage if user is logged
     */
    public function testDisplayEditTrickBtnsIfLogged()
    {
        $this->client->loginUser($this->userTest);
        $this->client->request('GET', '/');
        $this->assertSelectorExists('.trick-btns');
    }

    public function testHomepagePagination()
    {
        $crawler = $this->client->request('GET', '/100');
        $this->assertResponseIsSuccessful();
    }

    public function testFindTrickInHomepage()
    {
        $crawler = $this->client->request('GET', '/100');
        $find = $crawler->filter('h5.trick-name:contains("find")');
        $this->assertEquals(1, count($find));
    }
   
}