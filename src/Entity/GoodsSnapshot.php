<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GoodsSnapshotRepository")
 */
class GoodsSnapshot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $goodsId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tradeNo;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $goodsNum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $goodsName;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $goodsPrice;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $goodsImg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $goodsLink;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoodsId(): ?int
    {
        return $this->goodsId;
    }

    public function setGoodsId(int $goodsId): self
    {
        $this->goodsId = $goodsId;

        return $this;
    }

    public function getTradeNo(): ?string
    {
        return $this->tradeNo;
    }

    public function setTradeNo(string $tradeNo): self
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }

    public function getGoodsNum()
    {
        return $this->goodsNum;
    }

    public function setGoodsNum($goodsNum): self
    {
        $this->goodsNum = $goodsNum;

        return $this;
    }

    public function getGoodsName(): ?string
    {
        return $this->goodsName;
    }

    public function setGoodsName(string $goodsName): self
    {
        $this->goodsName = $goodsName;

        return $this;
    }

    public function getGoodsPrice()
    {
        return $this->goodsPrice;
    }

    public function setGoodsPrice($goodsPrice): self
    {
        $this->goodsPrice = $goodsPrice;

        return $this;
    }

    public function getGoodsImg(): ?string
    {
        return $this->goodsImg;
    }

    public function setGoodsImg(?string $goodsImg): self
    {
        $this->goodsImg = $goodsImg;

        return $this;
    }

    public function getGoodsLink(): ?string
    {
        return $this->goodsLink;
    }

    public function setGoodsLink(?string $goodsLink): self
    {
        $this->goodsLink = $goodsLink;

        return $this;
    }
}
