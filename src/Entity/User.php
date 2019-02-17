<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User 
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
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wxId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mbId;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getWxId(): ?int
    {
        return $this->wxId;
    }

    public function setWxId(?int $wxId): self
    {
        $this->wxId = $wxId;

        return $this;
    }

    public function getMbId(): ?int
    {
        return $this->mbId;
    }

    public function setMbId(?int $mbId): self
    {
        $this->mbId = $mbId;

        return $this;
    }
}
