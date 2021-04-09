<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccountValidator
{
    private UrlGeneratorInterface $urlGenerator;
    private MailerInterface $mailer;

    public function __construct(UrlGeneratorInterface $urlGenerator, MailerInterface $mailer)
    {
        $this->urlGenerator = $urlGenerator;
        $this->mailer = $mailer;
    }

    public function sendValidationMail(User $user): void
    {

        $url = $this->urlGenerator->generate('signup_confirm', [
            'token' => $user->getToken()
        ],UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('no-reply@snowtricks.com')
            ->to($user->getEmail())
            ->subject("Snowtricks - Validation de votre inscription")
            ->html($this->generateActivationMailHtmlContent($url));
        
        try {
            $this->mailer->send($email);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function sendResetPwdMail(User $user): void
    {
        $url = $this->urlGenerator->generate('reset_pwd', [
            'token' => $user->getToken()
        ],UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('no-reply@snowtricks.com')
            ->to($user->getEmail())
            ->subject("Snowtricks - Réinitilalisation de votre mot de passe")
            ->html($this->generateResetPwdMailHtmlContent($url));
        
        try {
            $this->mailer->send($email);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function generateActivationMailHtmlContent(string $url): string
    {
        return '<h3>Bienvenue au club !</h3>
        <p>
        Pour finaliser votre inscription, cliquez sur le lien suivant :
        <a href="'.$url.'">Valider mon inscription</a>
        </p>';
    }

    protected function generateResetPwdMailHtmlContent(string $url): string
    {
        return '<h3>Réinitialisation de votre mot de passe</h3>
        <p>
        Pour réinitialiser votre mot de passe, cliquez sur le lien suivant :
        <a href="'.$url.'">Réinitialiser votre mot de passe</a>
        </p>';
    }
}