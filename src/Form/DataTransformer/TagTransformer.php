<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Attribute;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TagRepository          $tagRepository
    )
    {
    }

    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        $tags = [];
        foreach ($value as $tag) {
            $tags[] = $tag->getName();
        }

        return implode(',', $tags);
    }

    public function reverseTransform(mixed $value = null): ArrayCollection
    {
        if (!$value) {
            return new ArrayCollection();
        }

        $items = explode(",", $value);
        $items = array_map('trim', $items);
        $items = array_unique($items);

        $value = new ArrayCollection();

        foreach ($items as $item) {
            $tag = $this->tagRepository->findOneBy(['name' => $item]);
            if (!$tag) {
                $tag = (new Tag())->setName($item);
            }

            $value->add($tag);
        }

        return $value;
    }
}
