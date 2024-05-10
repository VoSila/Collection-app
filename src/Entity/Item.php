<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * @var Collection<int, CustomItemAttributeValue>
     */
    #[ORM\OneToMany(targetEntity: CustomItemAttributeValue::class, mappedBy: 'item', cascade: ["persist"], orphanRemoval: true)]
    private Collection $customItemAttributeValues;

    public function __construct()
    {
        $this->customItemAttributeValues = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, CustomItemAttributeValue>
     */
    public function getCustomItemAttributeValues(): Collection
    {
        return $this->customItemAttributeValues;
    }

    public function addCustomItemAttributeValue(CustomItemAttributeValue $customItemAttributeValue): static
    {
        if (!$this->customItemAttributeValues->contains($customItemAttributeValue)) {
            $this->customItemAttributeValues->add($customItemAttributeValue);
            $customItemAttributeValue->setItem($this);
        }

        return $this;
    }

    public function removeCustomItemAttributeValue(CustomItemAttributeValue $customItemAttributeValue): static
    {
        if ($this->customItemAttributeValues->removeElement($customItemAttributeValue)) {
            // set the owning side to null (unless already changed)
            if ($customItemAttributeValue->getItem() === $this) {
                $customItemAttributeValue->setItem(null);
            }
        }

        return $this;
    }
}
