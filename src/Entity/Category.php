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
     * @ORM\OneToMany(targetEntity="App\Entity\Goods", mappedBy="category")
     */
    private $goods;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="underCategories")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parent")
     */
    private $underCategories;

    public function __construct()
    {
        $this->goods = new ArrayCollection();
        $this->underCategories = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUnderCategories(): Collection
    {
        return $this->underCategories;
    }

    public function addUnderCategory(self $underCategory): self
    {
        if (!$this->underCategories->contains($underCategory)) {
            $this->underCategories[] = $underCategory;
            $underCategory->setParent($this);
        }

        return $this;
    }

    public function removeUnderCategory(self $underCategory): self
    {
        if ($this->underCategories->contains($underCategory)) {
            $this->underCategories->removeElement($underCategory);
            // set the owning side to null (unless already changed)
            if ($underCategory->getParent() === $this) {
                $underCategory->setParent(null);
            }
        }

        return $this;
    }

    public function getGoodsBySort()
    {
        $goodses = $this->getGoods();
        $goodsArr = $goodses->toArray();
        usort($goodsArr,'self::cmp');
        return $goodsArr;
    }

     private function cmp($a, $b)
    {
        return $a->getSorter() > $b->getSorter() ? 1 : -1;
    }
}
