<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    #[Route('/generate-token', name: 'app_generate_api_token')]
    public function generateApiToken(Request $request): Response
    {
        try {
            $token = $this->userService->generateToken();
            $this->userService->saveCollection($token);
            $this->addFlash('success', $token);
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('errorCreate'));
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: $this->generateUrl('homepage'));    }
}
