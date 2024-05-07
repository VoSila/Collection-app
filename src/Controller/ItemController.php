<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\CollectionType;
use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    #[Route('/item/create', name: 'app_item_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
//            $this->entityManager->persist($collection);
//            $this->entityManager->flush();
//
//            $this->addFlash('success', 'Item successfully created');
        }

        return $this->render('item/form.html.twig', [
            'action' => 'create',
            'form' => $form
        ]);
    }
}
