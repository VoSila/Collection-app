<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ItemCollection::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemCollection $itemCollection = null;

    #[ORM\OneToOne(targetEntity: CustomItemAttribute::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?CustomItemAttribute $customItemAttribute = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemCollection(): ?ItemCollection
    {
        return $this->itemCollection;
    }

    public function setItemCollection(?ItemCollection $itemCollection): static
    {
        $this->itemCollection = $itemCollection;
        return $this;
    }

    public function getCustomItemAttribute(): ?CustomItemAttribute
    {
        return $this->customItemAttribute;
    }

    public function setCustomItemAttribute(?CustomItemAttribute $customItemAttribute): static
    {
        $this->customItemAttribute = $customItemAttribute;
        return $this;
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
