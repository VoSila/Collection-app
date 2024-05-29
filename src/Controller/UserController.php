<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/form.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

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
}
