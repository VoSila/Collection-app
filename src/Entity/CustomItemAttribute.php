<?php

namespace App\Entity;

use App\Enum\CustomAttributeType;
use App\Repository\CustomItemAttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomItemAttributeRepository::class)]
class CustomItemAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 10, enumType: CustomAttributeType::class)]
    private ?CustomAttributeType $type = null;

    #[ORM\ManyToOne(inversedBy: 'customItemAttributes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemCollection $itemCollection = null;

    #[ORM\OneToMany(targetEntity: CustomItemAttributeValue::class, mappedBy: 'customItemAttribute', cascade: ["persist"], orphanRemoval: true)]
    private Collection $customItemAttributeValues;

    public function __construct(){
        $this->customItemAttributeValues = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?CustomAttributeType
    {
        return $this->type;
    }

    public function setType(CustomAttributeType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getItemCollection(): ?ItemCollection
    {
        return $this->itemCollection;
    }

    public function setItemCollection(?ItemCollection $itemCollection): self
    {
        $this->itemCollection = $itemCollection;
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
            $customItemAttributeValue->setCustomItemAttribute($this);
        }

        return $this;
    }

    public function removeCustomItemAttributeValue(CustomItemAttributeValue $customItemAttributeValue): static
    {
        if ($this->customItemAttributeValues->removeElement($customItemAttributeValue)) {
            // set the owning side to null (unless already changed)
            if ($customItemAttributeValue->getCustomItemAttribute() === $this) {
                $customItemAttributeValue->setCustomItemAttribute(null);
            }
        }

        return $this;
    }
}
