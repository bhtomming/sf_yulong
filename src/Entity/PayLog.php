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
     * @ORM\Column(type="integer")
     */
    private $mbId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payNo;

    /**
     * @ORM\Column(type="float")
     */
    private $totalFee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payType;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="datetime")
     */
    private $payTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

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

    public function getPayNo(): ?string
    {
        return $this->payNo;
    }

    public function setPayNo(string $payNo): self
    {
        $this->payNo = $payNo;

        return $this;
    }

    public function getTotalFee(): ?float
    {
        return $this->totalFee;
    }

    public function setTotalFee(float $totalFee): self
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

    public function getPoints(): ?float
    {
        return $this->points;
    }

    public function setPoints(?float $points): self
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
}
