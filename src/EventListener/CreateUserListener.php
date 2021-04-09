<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\AccountValidator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class CreateUserListener
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private TokenGeneratorInterface $tokenGenerator;
    private AccountValidator $accountValidator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGeneratorInterface $tokenGenerator, AccountValidator $accountValidator)
    {
       $this->passwordEncoder = $passwordEncoder;
       $this->tokenGenerator = $tokenGenerator;
       $this->accountValidator = $accountValidator;
    }

    public function prePersist(User $user): void
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $user->setToken($this->tokenGenerator->generateToken());
        
        $this->accountValidator->sendValidationMail($user);
    }

}