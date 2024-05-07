<?php

namespace App\Controller;

use App\Entity\ItemCollection;
use App\Form\CollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CollectionController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security
    ){
    }

    #[Route('/collections', name: 'app_collection')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $collections = $entityManager->getRepository(ItemCollection::class)->find($id);

        return $this->render('collection/index.html.twig', [
            'collections' => $collections,
        ]);
    }

    #[Route('/collections/create', name: 'app_collection_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $user = $this->security->getUser();
        $collection = new ItemCollection();
        $form = $this->createForm(CollectionType::class, $collection);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $collection->setUser($user);
            $this->entityManager->persist($collection);
            $this->entityManager->flush();

            $this->addFlash('success', 'Collection successfully created');

            //render for collection
        }

        return $this->render('collection/form.html.twig', [
            'action' => 'create',
            'form' => $form
        ]);
    }

    #[Route('/collections/{id}/update', name: 'app_collection_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Request $request, ItemCollection $collection): Response
    {
        $form = $this->createForm(CollectionType::class, $collection);
        $form->handleRequest($request);

        $currentUser = $this->getUser();

        if ($collection->getUser() !== $currentUser) {
            throw $this->createAccessDeniedException('You are not allowed to access this collection.');
        }

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Collection successfully updated');
        }

        return $this->render('collection/form.html.twig', [
            'action' => 'update',
            'form' => $form,
            'collection' => $collection
        ]);
    }
}
