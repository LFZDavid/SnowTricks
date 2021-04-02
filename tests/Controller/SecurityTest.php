<?php

namespace App\Tests;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Security extends WebTestCase
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

    public function testGetSignUpForm()
    {
        $this->loadFixtures([
            'App\DataFixtures\TestFixtures'
        ], true);

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

    // public function testLogin()
    // {
    //     $crawler = $this->client->request('GET', '/login');
    //     $crawler = $this->client->submitForm('Connexion',[
    //         "email" => 'user@test.com',
    //         "password" => 'passwordTest',
    //     ]);
    //     $this->assertResponseRedirects();
    //     $crawler = $this->client->request('GET', '/');
    //     $this->assertSelectorExists('.logout-navlink');
    //     $this->assertSelectorNotExists('.signup-navlink');
    //     $this->assertSelectorNotExists('.login-navlink');
    // }
}
