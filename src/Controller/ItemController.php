<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Service\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/items')]
class ItemController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface   $entityManager,
        private readonly ItemService              $itemService,
        private readonly TranslatorInterface    $translator,
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
    public function create(Request $request, $id): Response
    {
        $result = $this->itemService->createItem($request, ItemType::class, $id);

        if ($result['success']) {
            $this->addFlash('success', 'Item successfully created');

            return $this->redirectToRoute('app_item', ['id' => $result['item']->getId()]);
        }

        return $this->render('item/form.html.twig', [
            'action' => $this->translator->trans('create'),
            'form' => $result['form']->createView()
        ]);

    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, Item $item, $id): Response
    {

        $result = $this->itemService->editItem($request, ItemType::class, $id);

        if ($result['success']) {
            $this->addFlash('success', 'Collection successfully created');

            return $this->redirectToRoute('app_collection_show', [
                'id' => $result['item']->getItemCollection()->getId()
            ]);
        }

        return $this->render('item/form.html.twig', [
            'action' => $this->translator->trans('edit'),
            'form' => $result['form']->createView(),
        ]);
    }

    #[NoReturn]
    #[Route('/{id}/delete', name: 'app_item_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($item);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_collection_show', [
            'id' => $request->query->getInt('collectionId')
        ], Response::HTTP_SEE_OTHER);
    }
}
