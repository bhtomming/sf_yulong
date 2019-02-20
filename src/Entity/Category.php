<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $decrectId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Goods", mappedBy="category")
     */
    private $goods;

    public function __construct()
    {
        $this->goods = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getDecrectId(): ?int
    {
        return $this->decrectId;
    }

    public function setDecrectId(?int $decrectId): self
    {
        $this->decrectId = $decrectId;

        return $this;
    }

    /**
     * @return Collection|Goods[]
     */
    public function getGoods(): Collection
    {
        return $this->goods;
    }

    public function addGood(Goods $good): self
    {
        if (!$this->goods->contains($good)) {
            $this->goods[] = $good;
            $good->setCategory($this);
        }

        return $this;
    }

    public function removeGood(Goods $good): self
    {
        if ($this->goods->contains($good)) {
            $this->goods->removeElement($good);
            // set the owning side to null (unless already changed)
            if ($good->getCategory() === $this) {
                $good->setCategory(null);
            }
        }

        return $this;
    }
}
