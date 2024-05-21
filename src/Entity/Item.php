<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateAt = null;

    /**
     * @var Collection<int, CustomItemAttributeValue>
     */
    #[ORM\OneToMany(targetEntity: CustomItemAttributeValue::class, mappedBy: 'item', cascade: ["persist"], orphanRemoval: true)]
    private Collection $customItemAttributeValues;

    #[ORM\ManyToOne(targetEntity: ItemCollection::class, cascade: ["remove"])]
    #[ORM\JoinColumn(name: 'item_collection_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?ItemCollection $itemCollection = null;

    #[ORM\JoinTable(name: 'tags_to_item')]
    #[ORM\JoinColumn(name: 'item_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: 'App\Entity\Tag', cascade: ['persist'], orphanRemoval: true)]
    private ArrayCollection|PersistentCollection $tags;

    public function __construct()
    {
        $this->customItemAttributeValues = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdateAtValue(): void
    {
        $this->updateAt = new \DateTime();
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

    public function getTags(): ArrayCollection|PersistentCollection
    {
        return $this->tags;
    }

    public function setTags(ArrayCollection $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public
    function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): void
    {
        $this->updateAt = $updateAt;
    }


}
