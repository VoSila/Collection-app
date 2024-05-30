<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class OAuthController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google_start')]
    public function redirectToGoogleConnect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect([
                'email', 'profile'
            ]);
    }

    #[Route('/connect/google/check', name: 'google_auth')]
    public function connectGoogleCheck(): RedirectResponse|JsonResponse
    {
        if (!$this->getUser()) {
            return new JsonResponse(['status' => false, 'message' => "User not found!"]);
        } else {
            return $this->redirectToRoute('app_main');
        }
    }

    #[Route('/connect/github', name: 'connect_github_start')]
    public function redirectToGithubConnect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('github')
            ->redirect([
                'user', 'public_repo'
            ]);
    }

    #[Route('/github/auth', name: 'github_auth')]
    public function authenticateGithubUser(): RedirectResponse|JsonResponse
    {
        if (!$this->getUser()) {
            return new JsonResponse(['status' => false, 'message' => "User not found!"]);
        } else {
            return $this->redirectToRoute('app_main');
        }
    }
}
