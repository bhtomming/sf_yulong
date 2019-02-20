<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeRepository")
 */
class Trade
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
    private $tradeNo;


    /**
     * @ORM\Column(type="integer")
     */
    private $goodsId;

    /**
     * @ORM\Column(type="float")
     */
    private $totalAmount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $examineTime;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $givePoints;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="trades")
     */
    private $member;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GoodsSnapshot", mappedBy="trade")
     */
    private $goodsSnapshot;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PayLog", inversedBy="trade", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $payLog;

    public function __construct()
    {
        $this->goodsSnapshot = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMbId(): ?int
    {
        return $this->mbId;
    }

    public function setMbId(int $mbId): self
    {
        $this->mbId = $mbId;

        return $this;
    }

    public function getPayId(): ?int
    {
        return $this->payId;
    }

    public function setPayId(?int $payId): self
    {
        $this->payId = $payId;

        return $this;
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

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getExamineTime(): ?\DateTimeInterface
    {
        return $this->examineTime;
    }

    public function setExamineTime(?\DateTimeInterface $examineTime): self
    {
        $this->examineTime = $examineTime;

        return $this;
    }

    public function getGivePoints(): ?float
    {
        return $this->givePoints;
    }

    public function setGivePoints(?float $givePoints): self
    {
        $this->givePoints = $givePoints;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    /**
     * @return Collection|GoodsSnapshot[]
     */
    public function getGoodsSnapshot(): Collection
    {
        return $this->goodsSnapshot;
    }

    public function addGoodsSnapshot(GoodsSnapshot $goodsSnapshot): self
    {
        if (!$this->goodsSnapshot->contains($goodsSnapshot)) {
            $this->goodsSnapshot[] = $goodsSnapshot;
            $goodsSnapshot->setTrade($this);
        }

        return $this;
    }

    public function removeGoodsSnapshot(GoodsSnapshot $goodsSnapshot): self
    {
        if ($this->goodsSnapshot->contains($goodsSnapshot)) {
            $this->goodsSnapshot->removeElement($goodsSnapshot);
            // set the owning side to null (unless already changed)
            if ($goodsSnapshot->getTrade() === $this) {
                $goodsSnapshot->setTrade(null);
            }
        }

        return $this;
    }

    public function getPayLog(): ?PayLog
    {
        return $this->payLog;
    }

    public function setPayLog(PayLog $payLog): self
    {
        $this->payLog = $payLog;

        return $this;
    }
}
