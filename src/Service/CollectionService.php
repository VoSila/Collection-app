<?php

namespace App\Service;

use App\Entity\CustomItemAttribute;
use App\Entity\Item;
use App\Entity\ItemCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class CollectionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FormFactoryInterface   $formFactory,
        private PaginatorInterface     $paginator,
        private Security               $security
    )
    {
    }

    public function createCollection(Request $request, string $formType): array
    {
        $collection = new ItemCollection();
        $form = $this->createForm($formType, $collection);
        $this->handleForm($form, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveCollection($collection);
            return [
                'success' => true,
                'form' => $form,
                'collection' => $collection
            ];
        }

        return [
            'success' => false,
            'form' => $form
        ];
    }

    public function editCollection(Request $request, string $formType, ItemCollection $collection): array
    {
        $form = $this->createForm($formType, $collection);
        $this->handleForm($form, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveEditedCollection();
            return [
                'success' => true,
                'form' => $form,
                'collection' => $collection
            ];
        }

        return [
            'success' => false,
            'form' => $form
        ];
    }

    public function saveCollection(ItemCollection $collection): void
    {
        $user = $this->security->getUser();
        $collection->setUser($user);
        $this->entityManager->persist($collection);
        $this->entityManager->flush();
    }

    public function saveEditedCollection(): void
    {
        $this->entityManager->flush();
    }

    public function createForm(string $formType, ItemCollection $collection): FormInterface
    {
        return $this->formFactory->create($formType, $collection);
    }

    public function handleForm(FormInterface $form, Request $request): void
    {
        $form->handleRequest($request);
    }

    public function getPagination(array $collections, int $request): PaginationInterface
    {
        return $pagination = $this->paginator->paginate(
            $collections,
            $request,
            10
        );
    }

    public function getCriteriaBySorting(?string $category): array|null
    {
        $criteria = [];
        if ($category !== null) {
            $criteria['category'] = $category;
        }

        return $criteria;
    }

    public function getCollectionsForMain(array $criteria, string $sortField, string $sortDirection): array
    {
        return $this->entityManager->getRepository(ItemCollection::class)->findBy($criteria, [$sortField => $sortDirection]);
    }

    public function getCollectionsForShow(int $collectionId): ItemCollection
    {
        return $this->entityManager->getRepository(ItemCollection::class)->find($collectionId);
    }

    public function getItemsForShow(int $collectionId): array
    {
        return $this->entityManager->getRepository(Item::class)->findBy(['itemCollection' => $collectionId]);
    }

    public function getCustomItemsForShow(int $collectionId): array
    {
        return $this->entityManager->getRepository(CustomItemAttribute::class)->findBy(['itemCollection' => $collectionId]);
    }
}
