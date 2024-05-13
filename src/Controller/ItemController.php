<?php

namespace App\Controller;

use App\Entity\CustomItemAttributeValue;
use App\Entity\Item;
use App\Entity\ItemCollection;
use App\Form\CollectionType;
use App\Form\ItemType;
use App\Repository\CustomItemAttributeRepository;
use App\Repository\ItemCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
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
    public function create(Request $request, EntityManagerInterface $entityManager, ItemCollectionRepository $itemCollectionRepository, FormFactoryInterface $formFactory): Response
    {
        $collectionId = 1;

        $collection = $itemCollectionRepository->getItemCollectionWithCustomAttributes($collectionId, $entityManager);

        $item = new Item();

        foreach ($collection->getCustomItemAttributes() as $customAttribute) {
            $customAttributeValue = new CustomItemAttributeValue();
            $customAttributeValue->setCustomItemAttribute($customAttribute);
            $item->addCustomItemAttributeValue($customAttributeValue);
        }

        $item->setItemCollection($collection);

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Item successfully created');

        }

        return $this->render('item/form.html.twig', [
            'action' => 'create',
            'form' => $form->createView()
        ]);
    }
}
