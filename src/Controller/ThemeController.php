<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    #[Route('/switch-theme/{theme}', name: 'app_switch_theme')]

    public function switchTheme(Request $request, $theme): RedirectResponse
    {
        if (!in_array($theme, ['app-light', 'app-dark'])) {
            throw $this->createNotFoundException('Theme not found');
        }

        $session = $request->getSession();
        $session->set('theme', $theme);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: $this->generateUrl('homepage'));
    }
}
