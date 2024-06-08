<?php

namespace App\Entity;

use App\Repository\CollectionCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CollectionCategoryRepository::class)]
class CollectionCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['collection:list', 'collection:create', 'collection:update'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['collection:list', 'collection:create', 'collection:update'])]
    private ?string $name = null;

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
