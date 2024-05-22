<?php

namespace App\Service\Main;

use App\Repository\ItemCollectionRepository;
use App\Repository\ItemRepository;
use App\Repository\TagRepository;

class MainService
{
    public function __construct(
        private readonly TagRepository            $tagRepository,
        private readonly ItemRepository           $itemRepository,
        private readonly ItemCollectionRepository $itemCollectionRepository
    )
    {
    }

    public function getItems()
    {
        return $this->itemRepository->findLastFiveWithEagerLoading();
    }

    public function getCollections(): array
    {
        $topFiveCollections = $this->itemRepository->findTopFiveLargestItemCollectionIds();

        $collectionIds = array_column($topFiveCollections, 'itemCollectionId');

        if (empty($collectionIds)) {
            return [];
        }

        return $this->itemCollectionRepository->getCategoriesWithItemCollections($collectionIds);
    }

    public function getTags(): array
    {
        return $this->tagRepository->getLastTags();
    }
}
