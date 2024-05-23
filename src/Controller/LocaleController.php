<?php

namespace App\Controller;

use App\Service\MainService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LocaleController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security,
    )
    {
    }

    #[Route('/change-locale/{locale}', name: 'app_change_locale')]
    public function changeLocale($locale, Request $request)
    {
        if (!in_array($locale, ['en', 'ru'])) {
            throw $this->createNotFoundException('Locale not supported');
        }

        $request->getSession()->set('_locale', $locale);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: $this->generateUrl('homepage'));
    }

}
