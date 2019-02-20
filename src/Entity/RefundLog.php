<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefundLogRepository")
 */
class RefundLog
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
     * @ORM\Column(type="integer")
     */
    private $payId;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $totalFee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $checker;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $checkedTime;

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

    public function setPayId(int $payId): self
    {
        $this->payId = $payId;

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

    public function getChecker(): ?int
    {
        return $this->checker;
    }

    public function setChecker(?int $checker): self
    {
        $this->checker = $checker;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCheckedTime(): ?\DateTimeInterface
    {
        return $this->checkedTime;
    }

    public function setCheckedTime(?\DateTimeInterface $checkedTime): self
    {
        $this->checkedTime = $checkedTime;

        return $this;
    }
}
