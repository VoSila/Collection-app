<?php

namespace App\Controller;

use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

 class TagController extends AbstractController
{
    public function __construct(private readonly TagService $tagService)
    {
    }

    #[Route('/autocomplete/tags', name: 'app_autocomplete_tags')]
    public function getTags(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('query');
        $tags = $this->tagService->getTags($searchTerm);

        return new JsonResponse($tags);
    }
}
