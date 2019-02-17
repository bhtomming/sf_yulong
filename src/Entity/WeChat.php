<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeChatRepository")
 */
class WeChat
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
    private $openid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $headImg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $qrcode;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $subscribe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recommendQrcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $provicne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $subscribeTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenid(): ?string
    {
        return $this->openid;
    }

    public function setOpenid(string $openid): self
    {
        $this->openid = $openid;

        return $this;
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getHeadImg(): ?string
    {
        return $this->headImg;
    }

    public function setHeadImg(?string $headImg): self
    {
        $this->headImg = $headImg;

        return $this;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(?string $qrcode): self
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    public function getSubscribe(): ?bool
    {
        return $this->subscribe;
    }

    public function setSubscribe(?bool $subscribe): self
    {
        $this->subscribe = $subscribe;

        return $this;
    }

    public function getRecommendQrcode(): ?string
    {
        return $this->recommendQrcode;
    }

    public function setRecommendQrcode(?string $recommendQrcode): self
    {
        $this->recommendQrcode = $recommendQrcode;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getProvicne(): ?string
    {
        return $this->provicne;
    }

    public function setProvicne(?string $provicne): self
    {
        $this->provicne = $provicne;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSubscribeTime(): ?\DateTimeInterface
    {
        return $this->subscribeTime;
    }

    public function setSubscribeTime(?\DateTimeInterface $subscribeTime): self
    {
        $this->subscribeTime = $subscribeTime;

        return $this;
    }
}
