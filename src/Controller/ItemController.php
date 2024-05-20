<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
use App\Form\ItemType;
use App\Repository\ItemCollectionRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/items')]
class ItemController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface   $entityManager,
        private readonly ItemCollectionRepository $itemCollectionRepository,
    )
    {
    }

    #[Route('/', name: 'app_item')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    #[Route('/{id}/create', name: 'app_item_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, TagRepository $tagRepository, $id): Response
    {

        $collection = $this->itemCollectionRepository->getItemCollectionWithCustomAttributes($id, $this->entityManager);
        $tags = $tagRepository->findAll();

        $item = new Item();
        $customAttributes = $collection->getCustomItemAttributes();

        $item->setItemCollection($collection);

        $form = $this->createForm(ItemType::class, $item, [
            'customAttributes' => $customAttributes,
            'tags' => $tags,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($item);
            $this->entityManager->flush();

            $this->addFlash('success', 'Item successfully created');
            $url = $request->getUriForPath("/items");

            return new RedirectResponse($url);
        }

        return $this->render('item/form.html.twig', [
            'action' => 'create',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, $id): Response
    {
        $item = $this->entityManager->getRepository(Item::class)->findWithEagerLoading($id);
        $collectionId = $item->getItemCollection()->getId();

        $collection = $this->itemCollectionRepository->getItemCollectionWithCustomAttributes($collectionId, $this->entityManager);
        $customAttributes = $collection->getCustomItemAttributes();

        $form = $this->createForm(ItemType::class, $item, [
            'customAttributes' => $customAttributes,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Item successfully updated');
        }

        return $this->render('item/form.html.twig', [
            'action' => 'edit',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/autocomplete/tags', name: 'autocomplete_tags')]
    public function getTags(Request $request): Response
    {
        $searchTerm = $request->query->get('query');
        $tags = $this->entityManager->getRepository(Tag::class)->createQueryBuilder('t')
            ->where('t.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();

        $results = [];
        foreach ($tags as $tag) {
            $results[] = [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }

        return new JsonResponse($results);

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
