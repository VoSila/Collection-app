<?php

namespace App\Controller;

use App\Service\MainService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security,
    )
    {
    }

    #[Route('/', name: 'app_main')]
    public function index(MainService $mainService): Response
    {
        return $this->render('main/index.html.twig', [
            'items' => $mainService->getItems(),
            'collections' => $mainService->getCollections(),
            'tags' => $mainService->getTags()
        ]);
    }
}
