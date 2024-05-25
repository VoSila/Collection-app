<?php

namespace App\Service;

use App\Entity\Item;
use App\Repository\ItemCollectionRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class ItemService
{
    public function __construct(
        private  ItemCollectionRepository $itemCollectionRepository,
        private EntityManagerInterface            $entityManager,
        private FormFactoryInterface              $formFactory,
        private TagRepository                     $tagRepository
    )
    {
    }

    public function createItem(Request $request, string $formType, $itemId): array
    {
        $collection = $this->getCollectionWithCustomAttributes($itemId);
        $customAttributes = $collection->getCustomItemAttributes();
        $tags = $this->getTags();

        $options = [
            'customAttributes' => $customAttributes,
            'tags' => $tags,
        ];

        $item = $this->createItemEntity($collection);
        $form = $this->createForm($formType, $item, $options);
        $this->handleForm($form, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveItem($item);
            return [
                'success' => true,
                'form' => $form,
                'item' => $item
            ];
        }

        return [
            'success' => false,
            'form' => $form
        ];
    }

    public function editItem(Request $request, string $formType, int $itemId): array
    {
        $item = $this->editItemEntity($itemId);
        $collectionId = $item->getItemCollection()->getId();
        $collection = $this->getCollectionWithCustomAttributes($collectionId);
        $customAttributes = $collection->getCustomItemAttributes();

        $options = [
            'customAttributes' => $customAttributes,
        ];

        $form = $this->createForm($formType, $item, $options);
        $this->handleForm($form, $request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveEditedItem();
            return [
                'success' => true,
                'form' => $form,
                'item' => $item
            ];
        }

        return [
            'success' => false,
            'form' => $form
        ];
    }

    public function saveItem(Item $item): void
    {
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    public function saveEditedItem(): void
    {
        $this->entityManager->flush();
    }

    public function createForm(string $formType, Item $item, $options): FormInterface
    {
        return $this->formFactory->create($formType, $item, $options);
    }

    public function handleForm(FormInterface $form, Request $request): void
    {
        $form->handleRequest($request);
    }

    public function createItemEntity($collection): Item
    {
        $item = new Item();
        $item->setItemCollection($collection);
        return $item;
    }

    public function editItemEntity(int $itemId): Item
    {
        return $this->entityManager->getRepository(Item::class)->findWithEagerLoading($itemId);
    }

    public function getTags()
    {
        return $this->tagRepository->findAll();
    }

    public function getCollectionWithCustomAttributes($itemId)
    {
        return $this->itemCollectionRepository->getItemCollectionWithCustomAttributes($itemId);
    }

    public function getCriteriaBySorting(?string $item): array|null
    {
        $criteria = [];
        if ($item !== null) {
            $criteria['item'] = $item;
        }

        return $criteria;
    }

    public function getItemsForShow(int $collectionId, string $sortField, string $sortDirection): array
    {
        return $this->entityManager->getRepository(Item::class)->findBy(['itemCollection' => $collectionId], [$sortField => $sortDirection]);
    }
}
