<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PayLogRepository")
 */
class PayLog
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * 支付单号
     */
    private $payNo;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $totalFee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payType;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 支付时间
     */
    private $payTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="payLog")
     */
    private $member;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Trade", mappedBy="payLog", cascade={"persist", "remove"})
     */
    private $trade;

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


    public function getPayNo(): ?string
    {
        return $this->payNo;
    }

    public function setPayNo(string $payNo): self
    {
        $this->payNo = $payNo;

        return $this;
    }

    public function getTotalFee()
    {
        return $this->totalFee;
    }

    public function setTotalFee($totalFee): self
    {
        $this->totalFee = $totalFee;

        return $this;
    }

    public function getPayType(): ?string
    {
        return $this->payType;
    }

    public function setPayType(string $payType): self
    {
        $this->payType = $payType;

        return $this;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function setPoints($points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getPayTime(): ?\DateTimeInterface
    {
        return $this->payTime;
    }

    public function setPayTime(\DateTimeInterface $payTime): self
    {
        $this->payTime = $payTime;

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

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getTrade(): ?Trade
    {
        return $this->trade;
    }

    public function setTrade(Trade $trade): self
    {
        $this->trade = $trade;

        // set the owning side of the relation if necessary
        if ($this !== $trade->getPayLog()) {
            $trade->setPayLog($this);
        }

        return $this;
    }
}
