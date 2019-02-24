<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 */
class Member
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $points;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $card;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cardAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nativePlace;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $realName;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="member", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PayLog", mappedBy="member")
     */
    private $payLog;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trade", mappedBy="member")
     */
    private $trades;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RefundLog", mappedBy="member")
     */
    private $refundLog;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exchange", mappedBy="member")
     */
    private $exchangeLog;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cash", mappedBy="member")
     */
    private $cashLog;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="members")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Member", mappedBy="parent")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Assess", mappedBy="member")
     */
    private $assess;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reply", mappedBy="member")
     */
    private $replies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PointsLog", mappedBy="member")
     */
    private $pointsLog;

    public function __construct()
    {
        $this->payLog = new ArrayCollection();
        $this->trades = new ArrayCollection();
        $this->refundLog = new ArrayCollection();
        $this->exchangeLog = new ArrayCollection();
        $this->cashLog = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->assess = new ArrayCollection();
        $this->replies = new ArrayCollection();
        $this->pointsLog = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPoints()
    {
        return $this->points;
    }

    public function setPoints($points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getCard(): ?string
    {
        return $this->card;
    }

    public function setCard(?string $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getCardAddress(): ?string
    {
        return $this->cardAddress;
    }

    public function setCardAddress(?string $cardAddress): self
    {
        $this->cardAddress = $cardAddress;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRealName(): ?string
    {
        return $this->realName;
    }

    public function setRealName(?string $realName): self
    {
        $this->realName = $realName;

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
        $newMember = $user === null ? null : $this;
        if ($newMember !== $user->member()) {
            $user->member($newMember);
        }

        return $this;
    }

    /**
     * @return Collection|PayLog[]
     */
    public function getPayLog(): Collection
    {
        return $this->payLog;
    }

    public function addPayLog(PayLog $payLog): self
    {
        if (!$this->payLog->contains($payLog)) {
            $this->payLog[] = $payLog;
            $payLog->setMember($this);
        }

        return $this;
    }

    public function removePayLog(PayLog $payLog): self
    {
        if ($this->payLog->contains($payLog)) {
            $this->payLog->removeElement($payLog);
            // set the owning side to null (unless already changed)
            if ($payLog->getMember() === $this) {
                $payLog->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Trade[]
     */
    public function getTrades(): Collection
    {
        return $this->trades;
    }

    public function addTrade(Trade $trade): self
    {
        if (!$this->trades->contains($trade)) {
            $this->trades[] = $trade;
            $trade->setMember($this);
        }

        return $this;
    }

    public function removeTrade(Trade $trade): self
    {
        if ($this->trades->contains($trade)) {
            $this->trades->removeElement($trade);
            // set the owning side to null (unless already changed)
            if ($trade->getMember() === $this) {
                $trade->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RefundLog[]
     */
    public function getRefundLog(): Collection
    {
        return $this->refundLog;
    }

    public function addRefundLog(RefundLog $refundLog): self
    {
        if (!$this->refundLog->contains($refundLog)) {
            $this->refundLog[] = $refundLog;
            $refundLog->setMember($this);
        }

        return $this;
    }

    public function removeRefundLog(RefundLog $refundLog): self
    {
        if ($this->refundLog->contains($refundLog)) {
            $this->refundLog->removeElement($refundLog);
            // set the owning side to null (unless already changed)
            if ($refundLog->getMember() === $this) {
                $refundLog->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exchange[]
     */
    public function getExchangeLog(): Collection
    {
        return $this->exchangeLog;
    }

    public function addExchangeLog(Exchange $exchangeLog): self
    {
        if (!$this->exchangeLog->contains($exchangeLog)) {
            $this->exchangeLog[] = $exchangeLog;
            $exchangeLog->setMember($this);
        }

        return $this;
    }

    public function removeExchangeLog(Exchange $exchangeLog): self
    {
        if ($this->exchangeLog->contains($exchangeLog)) {
            $this->exchangeLog->removeElement($exchangeLog);
            // set the owning side to null (unless already changed)
            if ($exchangeLog->getMember() === $this) {
                $exchangeLog->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cash[]
     */
    public function getCashLog(): Collection
    {
        return $this->cashLog;
    }

    public function addCashLog(Cash $cashLog): self
    {
        if (!$this->cashLog->contains($cashLog)) {
            $this->cashLog[] = $cashLog;
            $cashLog->setMember($this);
        }

        return $this;
    }

    public function removeCashLog(Cash $cashLog): self
    {
        if ($this->cashLog->contains($cashLog)) {
            $this->cashLog->removeElement($cashLog);
            // set the owning side to null (unless already changed)
            if ($cashLog->getMember() === $this) {
                $cashLog->setMember(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(self $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setParent($this);
        }

        return $this;
    }

    public function removeMember(self $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getParent() === $this) {
                $member->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Assess[]
     */
    public function getAssess(): Collection
    {
        return $this->assess;
    }

    public function addAssess(Assess $assess): self
    {
        if (!$this->assess->contains($assess)) {
            $this->assess[] = $assess;
            $assess->setMember($this);
        }

        return $this;
    }

    public function removeAssess(Assess $assess): self
    {
        if ($this->assess->contains($assess)) {
            $this->assess->removeElement($assess);
            // set the owning side to null (unless already changed)
            if ($assess->getMember() === $this) {
                $assess->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reply[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Reply $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setMember($this);
        }

        return $this;
    }

    public function removeReply(Reply $reply): self
    {
        if ($this->replies->contains($reply)) {
            $this->replies->removeElement($reply);
            // set the owning side to null (unless already changed)
            if ($reply->getMember() === $this) {
                $reply->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PointsLog[]
     */
    public function getPointsLog(): Collection
    {
        return $this->pointsLog;
    }

    public function addPointsLog(PointsLog $pointsLog): self
    {
        if (!$this->pointsLog->contains($pointsLog)) {
            $this->pointsLog[] = $pointsLog;
            $pointsLog->setMember($this);
        }

        return $this;
    }

    public function removePointsLog(PointsLog $pointsLog): self
    {
        if ($this->pointsLog->contains($pointsLog)) {
            $this->pointsLog->removeElement($pointsLog);
            // set the owning side to null (unless already changed)
            if ($pointsLog->getMember() === $this) {
                $pointsLog->setMember(null);
            }
        }

        return $this;
    }
}
