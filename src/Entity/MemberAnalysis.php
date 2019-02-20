<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberAnalysisRepository")
 */
class MemberAnalysis
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
     * @ORM\Column(type="string", length=255)
     */
    private $realName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $goodsCate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $payTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nativePlace;

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

    public function getRealName(): ?string
    {
        return $this->realName;
    }

    public function setRealName(string $realName): self
    {
        $this->realName = $realName;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getGoodsCate(): ?string
    {
        return $this->goodsCate;
    }

    public function setGoodsCate(string $goodsCate): self
    {
        $this->goodsCate = $goodsCate;

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

    public function getNativePlace(): ?string
    {
        return $this->nativePlace;
    }

    public function setNativePlace(?string $nativePlace): self
    {
        $this->nativePlace = $nativePlace;

        return $this;
    }
}
