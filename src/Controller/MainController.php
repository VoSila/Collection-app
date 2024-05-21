<?php

namespace App\Controller;

use App\Entity\CustomItemAttribute;
use App\Entity\CustomItemAttributeValue;
use App\Entity\Item;
use App\Entity\ItemCollection;
use App\Repository\ItemCollectionRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security,
    )
    {
    }

    #[Route('/', name: 'app_main')]
    public function index(ItemRepository $itemRepository, ItemCollectionRepository $itemCollectionRepository): Response
    {
        $topFiveCollections = $itemRepository->findTopFiveLargestItemCollectionIds();

        $collections = [];
        foreach ($topFiveCollections as $collection) {
            $collectionsId = $collection['itemCollectionId'];
            $collections[] = $itemCollectionRepository->getCategoryWithItemCollection($collectionsId);
        }

//        dd($collections);
        $items = $itemRepository->findLastFiveWithEagerLoading();

        return $this->render('main/index.html.twig', [
            'items' => $items,
            'collections' => $collections,
        ]);
    }
}
