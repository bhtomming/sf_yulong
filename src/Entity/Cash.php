<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CashRepository")
 */
class Cash
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
    private $mbId;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $checker;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $checkContent;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $checkTime;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

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

    public function getChecker(): ?int
    {
        return $this->checker;
    }

    public function setChecker(int $checker): self
    {
        $this->checker = $checker;

        return $this;
    }

    public function getCheckContent(): ?string
    {
        return $this->checkContent;
    }

    public function setCheckContent(?string $checkContent): self
    {
        $this->checkContent = $checkContent;

        return $this;
    }

    public function getCheckTime(): ?\DateTimeInterface
    {
        return $this->checkTime;
    }

    public function setCheckTime(?\DateTimeInterface $checkTime): self
    {
        $this->checkTime = $checkTime;

        return $this;
    }
}
