<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
final class User implements UserInterface
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
     * @ORM\Column(type="array")
     */
    private $roles;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Member", inversedBy="user", cascade={"persist", "remove"})
     * @ApiSubresource
     */
    private $member;



    /**
     * @ORM\OneToOne(targetEntity="App\Entity\RefundLog", inversedBy="checker", cascade={"persist", "remove"})
     */
    private $checkRefundLog;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cash", inversedBy="checker", cascade={"persist", "remove"})
     */
    private $checkCashLog;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\WeChat", inversedBy="user", cascade={"persist", "remove"})
     */
    private $wechat;



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

    public function hasRole($role)
    {
        if(!in_array($role,$this->roles))
        {
            return false;
        }
        return true;
    }


    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return md5(time());
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->name;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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


    public function getCheckRefundLog(): ?RefundLog
    {
        return $this->checkRefundLog;
    }

    public function setCheckRefundLog(?RefundLog $checkRefundLog): self
    {
        $this->checkRefundLog = $checkRefundLog;

        return $this;
    }

    public function getCheckCashLog(): ?Cash
    {
        return $this->checkCashLog;
    }

    public function setCheckCashLog(?Cash $checkCashLog): self
    {
        $this->checkCashLog = $checkCashLog;

        return $this;
    }

    public function getWechat(): ?WeChat
    {
        return $this->wechat;
    }

    public function setWechat(?WeChat $wechat): self
    {
        $this->wechat = $wechat;

        return $this;
    }

}
