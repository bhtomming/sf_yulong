<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PointsLogRepository")
 */
class PointsLog
{
    const CHONGZHI = 1;  //充值

    const CASH = 2; //提现

    const EXCHANGE = 3; //兑换

    const OWNPOINT = 4; //自己消费积分

    const DIRECTPOINT = 5; //下级消费积分

    const INDIRECTPOINT = 6; //间接下级消费积分

    const FAINDIRECTPOINT = 7; //第三代下级消费积分
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $points;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $changeReason;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $changeTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="pointsLog")
     */
    private $member;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logType;

    public function __construct()
    {
        $this->setChangeTime(new \DateTime('now'));
        $this->amount = 0;
        $this->points = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getChangeReason(): ?string
    {
        return $this->changeReason;
    }

    public function setChangeReason(?string $changeReason): self
    {
        $this->changeReason = $changeReason;

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

    public function getChangeTime(): ?\DateTimeInterface
    {
        return $this->changeTime;
    }

    public function setChangeTime(\DateTimeInterface $changeTime): self
    {
        $this->changeTime = $changeTime;

        return $this;
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

    public function getLogType(): ?string
    {
        return $this->logType;
    }

    public function setLogType(?string $logType): self
    {
        $this->logType = $logType;

        return $this;
    }
}
