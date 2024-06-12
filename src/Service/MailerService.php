<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;

class MailerService
{
    public function __construct(
        #[Autowire('%admin_email%')]  private string $adminEmail,
        private readonly MailerInterface $mailer)
    {

    }

    public function sendWelcomeEmail(string $userEmail, string $firstName): void
    {
        
        $email = (new NotificationEmail())
            ->from($this->adminEmail)
            ->to($userEmail)
            ->htmlTemplate('emails/welcome.html.twig', [
                'firstName' => $firstName,
            ])
            ->subject('Bienvenue' . $firstName)
            ->context([
                'firstName' => $firstName,
            ]);

        $this->mailer->send($email);
    }
}