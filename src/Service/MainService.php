<?php

namespace App\Service;

use App\Repository\ItemCollectionRepository;
use App\Repository\ItemRepository;
use App\Repository\TagRepository;

readonly class MainService
{
    public function __construct(
        private ItemCollectionRepository $itemCollectionRepository,
        private ItemRepository           $itemRepository,
        private TagRepository            $tagRepository
    )
    {
    }

    public function getItems()
    {
        return $this->itemRepository->findLastSixWithEagerLoading();
    }

    public function getCollections(): array
    {
        $topFiveCollections = $this->itemRepository->findTopSixLargestItemCollectionIds();

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
