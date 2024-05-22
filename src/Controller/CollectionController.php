<?php

namespace App\Controller;

use App\Entity\ItemCollection;
use App\Form\CollectionType;
use App\Service\CollectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/collections')]
class CollectionController extends AbstractController
{
    public function __construct(
        private readonly CollectionService $collectionService,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_collection')]
    public function index(Request $request): Response
    {
        $category = $request->get('category');
        $sortField = $request->query->get('sort', 'id');
        $sortDirection = $request->query->get('direction', 'DESC');

        $criteria = $this->collectionService->getCriteriaBySorting($category);
        $collections = $this->collectionService->getCollectionsForMain($criteria, $sortField, $sortDirection);
        $pagination = $this->collectionService->getPagination($collections, $request->query->getInt('page', 1));

        return $this->render('collection/index.html.twig', [
            'pagination' => $pagination,
            'collections' => $collections,
            'queryParams' => $request->query->all(),
        ]);
    }

    #[Route('/{id}/show', name: 'app_collection_show')]
    #[IsGranted('view', 'collection', 'Collection not found', 404)]
    public function show(Request $request, ItemCollection $collection, $id): Response
    {
        $items = $this->collectionService->getItemsForShow($id);
        $collection = $this->collectionService->getCollectionsForShow($id);
        $customItems = $this->collectionService->getCustomItemsForShow($id);
        $pagination = $this->collectionService->getPagination($items, $request->query->getInt('page', 1));

        return $this->render('collection/show.html.twig', [
            'pagination' => $pagination,
            'collection' => $collection,
            'customItems' => $customItems,
        ]);

    }

    #[Route('/{id}/edit', name: 'app_collection_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[IsGranted('edit', 'collection', 'Collection not found', 404)]
    public function edit(Request $request, ItemCollection $collection): Response
    {
        $result = $this->collectionService->editCollection($request, CollectionType::class, $collection);

        if ($result['success']) {
            $this->addFlash('success', 'Collection successfully created');

            return $this->redirectToRoute('app_collection');
        }

        return $this->render('collection/form.html.twig', [
            'action' => 'Edit',
            'form' => $result['form']->createView(),
            'collection' => $collection
        ]);
    }

    #[Route('/create', name: 'app_collection_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $result = $this->collectionService->createCollection($request, CollectionType::class);

        if ($result['success']) {
            $this->addFlash('success', 'Collection successfully created');

            return $this->redirectToRoute('app_collection_show', ['id' => $result['collection']->getId()]);
        }

        return $this->render('collection/form.html.twig', [
            'action' => 'Create',
            'form' => $result['form']->createView()
        ]);
    }

    #[Route('/{id}/delete', name: 'app_collection_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, ItemCollection $itemCollection,): Response
    {

        if ($this->isCsrfTokenValid('delete' . $itemCollection->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($itemCollection);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_collection', [], Response::HTTP_SEE_OTHER);
    }
}
