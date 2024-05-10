<?php

namespace App\Controller;

use App\Entity\CustomItemAttributeValue;
use App\Entity\Item;
use App\Entity\ItemCollection;
use App\Form\CollectionType;
use App\Form\ItemType;
use App\Repository\CustomItemAttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
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
    public function create(Request $request , EntityManagerInterface $entityManager, CustomItemAttributeRepository $customItemAttributeRepository): Response
    {
        $item = new Item();

        $collectionId = '2';
        $customItemAttribute = $customItemAttributeRepository->find($collectionId);

        $customItemAttributeValue = new CustomItemAttributeValue();
        $customItemAttributeValue->setCustomItemAttribute($customItemAttribute);

        $item->addCustomItemAttributeValue($customItemAttributeValue);

        $form = $this->createForm(ItemType::class, $item, [
            'customItemAttribute' => $customItemAttribute,
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($item);
            $entityManager->flush();
        }

        return $this->render('item/form.html.twig', [
            'action' => 'create',
            'form' => $form->createView()
        ]);
    }
}
