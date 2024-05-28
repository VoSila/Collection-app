<?php

namespace App\Controller;

use App\Service\MainService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
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
