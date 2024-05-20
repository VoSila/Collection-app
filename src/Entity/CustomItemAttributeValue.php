<?php

namespace App\Entity;

use App\Repository\CustomItemAttributeValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomItemAttributeValueRepository::class)]
class CustomItemAttributeValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'json')]
    private mixed $value = null;

    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: 'customItemAttributeValues')]
    #[ORM\JoinColumn(name: 'item_id', referencedColumnName: 'id')]
    private ?Item $item = null;

    #[ORM\ManyToOne(targetEntity: CustomItemAttribute::class)]
    #[ORM\JoinColumn(name: 'custom_item_attribute_id', referencedColumnName: 'id')]
    private ?CustomItemAttribute $customItemAttribute = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

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
}
