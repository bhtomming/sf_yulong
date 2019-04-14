<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeRepository")
 */
class Exchange
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $exchangeType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="exchangeLog")
     */
    private $member;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $changeNo;

    public function __construct()
    {
        $this->changeNo = "CZ".date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $this->exchangeType = "微信充值";
        $this->status = "未到账";
        $this->createdTime = new \DateTime('now');
    }

    public function getPayInfo(){
        return array(
            'out_trade_no' => $this->getChangeNo(),
            'total_fee' => $this->amount * 100, // 订单金额**单位：分**
            'body' => '支付玉泷网络服务',
            'attach' => 'CZ',
        );
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

    public function getExchangeType(): ?string
    {
        return $this->exchangeType;
    }

    public function setExchangeType(string $exchangeType): self
    {
        $this->exchangeType = $exchangeType;

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

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getChangeNo(): ?string
    {
        return $this->changeNo;
    }

    public function setChangeNo(string $changeNo): self
    {
        $this->changeNo = $changeNo;

        return $this;
    }
}
