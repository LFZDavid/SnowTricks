<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Security extends WebTestCase
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
            "user[name]" => 'User Create Test',
            "user[email]" => 'create@test.com',
            "user[password]" => 'userpassword',
            "user[confirm_password]" => 'userpassword',
        ]);
        $this->assertSelectorNotExists('span.form-error-message');
        $this->assertResponseRedirects();
    }

    public function testgetLoginForm()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    public function testNotAccessLoginFormForLoggedUser()
    {
        $this->client->loginUser($this->userTest);
        $crawler = $this->client->request('GET', '/login');
        $this->assertResponseRedirects();
    }

    public function testWrongLoginFormSubmit()
    {
        $crawler = $this->client->request('GET', '/login');
        $crawler = $this->client->submitForm('Connexion',[
            "email" => 'wrong@test.com',
            "password" => 'wrongpassword',
        ]);
        $this->assertSelectorNotExists('div.alert-danger');
        $this->assertResponseRedirects();
    }
    
}
