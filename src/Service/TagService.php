<?php

namespace App\Service;

use App\Repository\TagRepository;

readonly class TagService
{
    public function __construct(
        private TagRepository $tagRepository
    )
    {
    }

    public function getTags(string $searchTerm): array
    {
        $tags = $this->tagRepository->findBySearchTerm($searchTerm);

        $results = [];
        foreach ($tags as $tag) {
            $results["results"][] = [
                "value" => $tag->getId(),
                "text" => $tag->getName(),
            ];
        }

        return $results;
    }
}
