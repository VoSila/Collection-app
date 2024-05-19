<?php

namespace App\Controller;

use App\Entity\CustomItemAttributeValue;
use App\Entity\Item;
use App\Entity\ItemCollection;
use App\Form\CollectionType;
use App\Form\ItemType;
use App\Repository\ItemCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/items')]
class ItemController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ){
    }
    #[Route('/', name: 'app_item')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    #[Route('/create', name: 'app_item_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $entityManager, ItemCollectionRepository $itemCollectionRepository): Response
    {
        $collectionId = 1;

        $collection = $itemCollectionRepository->getItemCollectionWithCustomAttributes($collectionId, $entityManager);

        $item = new Item();
        $customAttributes = $collection->getCustomItemAttributes();

        $item->setItemCollection($collection);

        $form = $this->createForm(ItemType::class, $item, [
            'customAttributes' => $customAttributes,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Item successfully created');

            $url = $request->getUriForPath("/items");

            return new RedirectResponse($url);

        }

        return $this->render('item/form.html.twig', [
            'action' => 'create',
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/update', name: 'app_item_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Request $request, Item $item): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

//        $currentUser = $this->getUser();

//        if ($item->getUser() !== $currentUser) {
//            throw $this->createAccessDeniedException('You do not have access to this collection item.');
//        }

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Item successfully updated');
        }

        return $this->render('item/form.html.twig', [
            'action' => 'update',
            'form' => $form->createView(),
        ]);
    }

//    #[Route('/{id}/delete', name: 'app_collection_delete', methods: [Request::METHOD_GET, Request::METHOD_POST])]
//    public function delete(Request $request, Item $item): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
//            $this->entityManager->remove($item);
//            $this->entityManager->flush();
//        }
//
//        return $this->render('item/form.html.twig', [
//            'action' => 'delete',
//        ]);
//    }
}
