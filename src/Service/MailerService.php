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

    public function sendWelcomeEmail(): void
    {
        
        $email = (new NotificationEmail())
            ->from($this->adminEmail)
            ->to($this ->adminEmail)
            ->subject('Welcome to our website')
            ->text('Thank you for signing up to our website!');

        $this->mailer->send($email);
    }
}