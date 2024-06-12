<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailerService;
use Psr\Log\LoggerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): JsonResponse
    {
        return new JsonResponse(['status' => 'OK']);
    }

    #[Route('/mailer/mail/welcome', name: 'app_welcome_mail', methods: ['POST'])]
    public function welcomeEmail(Request $request, MailerService $mailerService, LoggerInterface $logger): JsonResponse
    {
        $logger->info('Received request at /mail/welcome');
        
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            $logger->error('Invalid JSON received');
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        $userEmail = $data['userEmail'] ?? null;
        $firstName = $data['firstName'] ?? null;

        if (!$userEmail || !$firstName) {
            $logger->error('Missing parameters');
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }

        $mailerService->sendWelcomeEmail($userEmail, $firstName);

        $logger->info('Email sent to ' . $userEmail);

        return new JsonResponse(['status' => 'Email sent']);
    }
}