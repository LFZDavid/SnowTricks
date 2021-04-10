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

    /**
     * Check route and if form is prensent
     */
    public function testGetSignUpForm()
    {
        $this->client->request('GET', '/user/signup');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    
    /**
     * Test if errors is displayed whene submit incomplete infos
     */
    public function testIncompleteSubmitSignUpForm()
    {
        $this->client->request('GET', '/user/signup');
        $this->client->submitForm('Enregistrer', [
            "user[password][first]" => 'azeaze'
        ]);
        $this->assertSelectorExists('span.form-error-message');
    }

    /**
     * Test if create user works
     * no error message is displayed
     * Redirection if success
     */
    public function testCreateUser()
    {
        $this->client->request('GET', '/user/signup');
        $this->client->submitForm('Enregistrer', [
            "user[name]" => 'User Create Test',
            "user[email]" => 'create@test.com',
            "user[password][first]" => 'userpassword',
            "user[password][second]" => 'userpassword',
        ]);
        $this->assertSelectorNotExists('span.form-error-message');
        $this->assertResponseRedirects();
        
        /** Check success message */
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');

        /** Assert New user is added in database */
        $createdUser = $this->userRepository->findOneByEmail('create@test.com');
        $this->assertNotNull($createdUser);
    }

    /**
     * Check route and if form is prensent
     */
    public function testgetLoginForm()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Check if login form works and redirect to home
     */
    public function testLoginUser()
    {
        $this->client->request('GET', '/login');
        $this->client->submitForm('Connexion', [
            'name' => 'valid',
            'password' => 'valid',
        ]);
        $this->assertSelectorNotExists('span.form-error-message');
        $this->assertResponseRedirects('/');
    }

    public function testWrongLoginFormSubmit()
    {
        $this->client->request('GET', '/login');
        $this->client->submitForm('Connexion',[
            "name" => 'wrongName',
            "password" => 'wrongpassword',
        ]);
        $this->assertSelectorNotExists('div.alert-danger');
        $this->assertResponseRedirects();
    }

    public function testLogoutBtn()
    {
        $this->client->loginUser($this->userTest);
        $this->client->request('GET', '/');
        $this->assertNotNull($this->userTest);
        $this->assertSelectorExists('.logout-navlink');
        $this->client->clickLink('Deconnexion');

        /** Check if logout btn is not display */
        $this->assertSelectorNotExists('.logout-navLink');
    }
    
    public function testCantGetAccountFormIfNotLogged()
    {
        $this->client->request('GET', '/user/account');
        $this->assertResponseRedirects();
    }

    public function testAccountForm()
    {
        $this->client->loginUser($this->userTest);
        $this->client->request('GET', '/user/account');
        $this->assertResponseIsSuccessful();
        $this->assertInputValueSame('account[name]', $this->userTest->getName());
        $this->assertInputValueSame('account[email]', $this->userTest->getEmail());
        $this->assertSelectorExists('.forgot-pwd-link');
    }

    public function testUpdateUser()
    {
        $this->client->loginUser($this->userTest);
        $newName = $this->userTest->getName().' modifié';
        $this->client->request('GET', '/user/account');
        $this->client->submitForm('Enregistrer', [
            "account[name]" => $newName,
            "account[email]" => $this->userTest->getEmail(),
        ]);
        $this->assertSelectorNotExists('span.form-error-message');
        $this->assertResponseRedirects();
        
        $this->client->request('GET', '/user/account');
        $this->assertInputValueSame('account[name]', $newName);
    }
    
    public function testGetForgotPwdForm()
    {
        $this->client->request('GET', '/security/lost_pwd');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test if it's impossible to access reset_pwd whithout token
     */
    public function testCantAccessResetPwdFormWhithoutToken()
    {
        $this->client->request('GET','/security/reset_pwd');
        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * Test get reset_password form submit
     */
    public function testGetResetPwdForm()
    {
        $this->userTest;
        $this->client->request('GET', '/security/reset_pwd/'.$this->userTest->getToken());
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test wrong reset password form submit
     */
    public function testWrongSubmitResetPwdForm()
    {
        $this->userTest;
        $this->client->request('GET', '/security/reset_pwd/'.$this->userTest->getToken());
        $this->client->submitForm('Enregistrer', [
            "reset_pwd[password][first]" => 'wrong',
            "reset_pwd[password][second]" => 'notEqual',
        ]);
        $this->assertSelectorExists('span.form-error-message');
    }

    /**
     * Test reset password form 
     * assert that password has changed
     */
    public function testGoodSubmitResetPwdForm()
    {
        $new_password = 'new_password';
        $this->userTest;
        $this->client->request('GET', '/security/reset_pwd/'.$this->userTest->getToken());
        $this->client->submitForm('Enregistrer', [
            "reset_pwd[password][first]" => $new_password,
            "reset_pwd[password][second]" => $new_password,
        ]);
        $updatedUser = $this->userRepository->findOneByEmail('valid@test.com');
        $this->assertNotEquals($this->userTest->getPassword(), $updatedUser->getPassword());
        $this->assertResponseRedirects();
        $this->assertSelectorNotExists('span.form-error-message');

    }

}
