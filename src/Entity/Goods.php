<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GoodsRepository")
 */
class Goods
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
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="integer")
     */
    private $categoryId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titleImg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $swiperImg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $voide;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFront;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sorter;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hot;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discountPrice;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="boolean")
     */
    private $saling;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pointExchange;

    /**
     * @ORM\Column(type="integer")
     */
    private $sale;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $evaluateId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $downTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="goods")
     */
    private $category;

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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getTitleImg(): ?string
    {
        return $this->titleImg;
    }

    public function setTitleImg(?string $titleImg): self
    {
        $this->titleImg = $titleImg;

        return $this;
    }

    public function getSwiperImg(): ?string
    {
        return $this->swiperImg;
    }

    public function setSwiperImg(?string $swiperImg): self
    {
        $this->swiperImg = $swiperImg;

        return $this;
    }

    public function getVoide(): ?string
    {
        return $this->voide;
    }

    public function setVoide(?string $voide): self
    {
        $this->voide = $voide;

        return $this;
    }

    public function getIsFront(): ?bool
    {
        return $this->isFront;
    }

    public function setIsFront(bool $isFront): self
    {
        $this->isFront = $isFront;

        return $this;
    }

    public function getSorter(): ?int
    {
        return $this->sorter;
    }

    public function setSorter(?int $sorter): self
    {
        $this->sorter = $sorter;

        return $this;
    }

    public function getHot(): ?bool
    {
        return $this->hot;
    }

    public function setHot(bool $hot): self
    {
        $this->hot = $hot;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice($discountPrice): self
    {
        $this->discountPrice = $discountPrice;

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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSaling(): ?bool
    {
        return $this->saling;
    }

    public function setSaling(bool $saling): self
    {
        $this->saling = $saling;

        return $this;
    }

    public function getPointExchange(): ?bool
    {
        return $this->pointExchange;
    }

    public function setPointExchange(bool $pointExchange): self
    {
        $this->pointExchange = $pointExchange;

        return $this;
    }

    public function getSale(): ?int
    {
        return $this->sale;
    }

    public function setSale(int $sale): self
    {
        $this->sale = $sale;

        return $this;
    }

    public function getEvaluateId(): ?int
    {
        return $this->evaluateId;
    }

    public function setEvaluateId(?int $evaluateId): self
    {
        $this->evaluateId = $evaluateId;

        return $this;
    }

    public function getCreatedTime(): ?\DateTimeInterface
    {
        return $this->createdTime;
    }

    public function setCreatedTime(\DateTimeInterface $createdTime): self
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    public function getPublishTime(): ?\DateTimeInterface
    {
        return $this->publishTime;
    }

    public function setPublishTime(?\DateTimeInterface $publishTime): self
    {
        $this->publishTime = $publishTime;

        return $this;
    }

    public function getDownTime(): ?\DateTimeInterface
    {
        return $this->downTime;
    }

    public function setDownTime(?\DateTimeInterface $downTime): self
    {
        $this->downTime = $downTime;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
