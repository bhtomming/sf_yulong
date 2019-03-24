<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeChatRepository")
 */
class WeChat implements UserInterface
{
    const CHAT_ROLE = 'ROLE_USER';
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

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="wechat", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $salt;

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
        if($subscribe){
            $this->setSubscribeTime(new \DateTime('now'));
        }
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newWechat = $user === null ? null : $this;
        if ($newWechat !== $user->getWechat()) {
            $user->setWechat($newWechat);
        }

        return $this;
    }


    public function getRoles()
    {
        return $this->roles ? $this->roles : [self::CHAT_ROLE];
    }

    public function addRole($role)
    {
        if(in_array($role,$this->roles))
        {
            return $this;
        }
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
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
        return $this->openid;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;
        if(empty($roles))
        {
            $this->roles[] = self::CHAT_ROLE;
        }
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }
}
