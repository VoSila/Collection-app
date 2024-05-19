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
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/collections')]
class CollectionController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security
    )
    {
    }

    #[Route('/', name: 'app_collection')]
    public function index(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        if ($user instanceof UserInterface) {
            $userId = $user->getId();
            $collections = $entityManager->getRepository(ItemCollection::class)->findBy(['user' => $userId]);

            return $this->render('collection/index.html.twig', [
                'collections' => $collections,
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

//            dd($form);

            $collection->setUser($user);
            $this->entityManager->persist($collection);
            $this->entityManager->flush();

            $this->addFlash('success', 'Collection successfully created');

            //render for collection
        }

        return $this->render('collection/form.html.twig', [
            'action' => 'create',
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/update', name: 'app_collection_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Request $request, ItemCollection $collection): Response
    {
        $form = $this->createForm(CollectionType::class, $collection);

        $form->handleRequest($request);

//        $currentUser = $this->getUser();

//        if ($collection->getUser() !== $currentUser) {
//            throw $this->createAccessDeniedException('You are not allowed to access this collection.');
//        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Collection successfully updated');
        }

        return $this->render('collection/form.html.twig', [
            'action' => 'update',
            'form' => $form->createView(),
            'collection' => $collection
        ]);
    }
}
