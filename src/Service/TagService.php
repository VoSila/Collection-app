<?php

namespace App\Service;

use App\Repository\TagRepository;

class TagService
{
    public function __construct(private TagRepository $tagRepository
    )
    {
    }

    public function getTags(string $searchTerm): array
    {
        $tags = $this->tagRepository->findBySearchTerm($searchTerm);

        $results = [];
        foreach ($tags as $tag) {
            $results[] = [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }

        return $results;
    }
}
