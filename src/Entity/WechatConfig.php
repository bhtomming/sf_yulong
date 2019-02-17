<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WechatConfigRepository")
 */
class WechatConfig
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
    private $appid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $access_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $appscret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $expiresIn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reply;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppid(): ?string
    {
        return $this->appid;
    }

    public function setAppid(string $appid): self
    {
        $this->appid = $appid;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setAccessToken(?string $access_token): self
    {
        $this->access_token = $access_token;

        return $this;
    }

    public function getAppscret(): ?string
    {
        return $this->appscret;
    }

    public function setAppscret(?string $appscret): self
    {
        $this->appscret = $appscret;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiresIn(): ?string
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?string $expiresIn): self
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function getReply(): ?string
    {
        return $this->reply;
    }

    public function setReply(?string $reply): self
    {
        $this->reply = $reply;

        return $this;
    }
}
