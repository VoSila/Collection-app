<?php

namespace App\Controller;

use App\Entity\CustomItemAttribute;
use App\Entity\CustomItemAttributeValue;
use App\Entity\Item;
use App\Entity\ItemCollection;
use App\Form\CollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/collections')]
class CollectionController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security,
        private PaginatorInterface              $paginator,
    )
    {
    }

    #[Route('/', name: 'app_collection')]
    public function index(Request $request): Response
    {
        $category = $request->get('category');
        $criteria = [];

        $sortField = $request->query->get('sort', 'id');
        $sortDirection = $request->query->get('direction', 'DESC');


        if ($category !== null) {
            $criteria['category'] = $category;
        }

        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            $userId = $user->getId();

            $collections = $this->entityManager->getRepository(ItemCollection::class)->findBy($criteria, [$sortField => $sortDirection]);
//            $collections = $this->entityManager->getRepository(ItemCollection::class)->findBy(['user' => $userId]);

            $pagination = $this->paginator->paginate(
                $collections,
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('collection/index.html.twig', [
                'pagination' => $pagination,
                'collections' => $collections,
                'queryParams' => $request->query->all(),

            ]);
        } else {
            dd('User Not Authorize');
        }
    }

    #[Route('/{id}/show', name: 'app_collection_show')]
    public function show(Request $request, $id): Response
    {
        $user = $this->security->getUser();
        if ($user instanceof UserInterface) {

            $collection = $this->entityManager->getRepository(ItemCollection::class)->find($id);
            $items = $this->entityManager->getRepository(Item::class)->findBy(['itemCollection' => $id]);
            $customItems = $this->entityManager->getRepository(CustomItemAttribute::class)->findBy(['itemCollection' => $id]);

            $pagination = $this->paginator->paginate(
                $items,
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('collection/show.html.twig', [
                'pagination' => $pagination,
                'collection' => $collection,
                'customItems' => $customItems,
            ]);
        } else {
            dd('User Not Authorize');
        }
    }

    #[Route('/create', name: 'app_collection_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $user = $this->security->getUser();
        $collection = new ItemCollection();
        $form = $this->createForm(CollectionType::class, $collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $collection->setUser($user);
            $this->entityManager->persist($collection);
            $this->entityManager->flush();

            $this->addFlash('success', 'Collection successfully created');

            //render for collection
        }

        return $this->render('collection/form.html.twig', [
            'action' => 'Create',
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_collection_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, ItemCollection $collection): Response
    {
        $form = $this->createForm(CollectionType::class, $collection);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Collection successfully updated');
        }

        return $this->render('collection/form.html.twig', [
            'action' => 'Edit',
            'form' => $form->createView(),
            'collection' => $collection
        ]);
    }

    #[Route('/{id}/delete', name: 'app_collection_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, ItemCollection $itemCollection,): Response
    {

//        dd($this->isCsrfTokenValid('delete'.$itemCollection->getId(), $request->request->get('_token')));

        if ($this->isCsrfTokenValid('delete' . $itemCollection->getId(), $request->request->get('_token'))) {
//            dd($itemCollection);
            $this->entityManager->remove($itemCollection);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_collection', [], Response::HTTP_SEE_OTHER);
    }
}
