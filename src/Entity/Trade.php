<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeRepository")
 */
class Trade
{
    const UNPAY = 0; //未付款
    const PAIED = 1;  //已付款
    const CLOSE = 2;  //已关闭

    const UNSEND = 0;
    const SENDING = 1;
    const RECIVER = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tradeNo;


    /**
     * @ORM\Column(type="float")
     */
    private $totalAmount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $examineTime;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $givePoints;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="trades")
     */
    private $member;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GoodsSnapshot", mappedBy="trade")
     */
    private $goodsSnapshot;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PayLog", inversedBy="trade", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $payLog;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logisticsStatus;

    public function __construct()
    {
        $this->goodsSnapshot = new ArrayCollection();
        mt_srand((double) microtime() * 1000000);
        $this->tradeNo = "DD".date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $this->status = self::UNPAY;
        $this->logisticsStatus = self::UNSEND;
        $dateTime = new \DateTime('now');
        $dateTime->modify("+30 minute");
        $this->setExamineTime($dateTime);
    }

    public function getWePayInfo()
    {
        return array(
            'out_trade_no' => time(),
            'total_fee' => $this->totalAmount * 100, // 订单金额**单位：分**
            'body' => '支付'.$this->tradeNo,
            'openid' => 'onkVf1FjWS5SBIixxxxxxx',
            'attach' => 'goods',
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTradeNo(): ?string
    {
        return $this->tradeNo;
    }

    public function setTradeNo(string $tradeNo): self
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }




    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

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

    public function getExamineTime(): ?\DateTimeInterface
    {
        return $this->examineTime;
    }

    public function setExamineTime(?\DateTimeInterface $examineTime): self
    {
        $this->examineTime = $examineTime;

        return $this;
    }

    public function getGivePoints(): ?float
    {
        return $this->givePoints;
    }

    public function setGivePoints(?float $givePoints): self
    {
        $this->givePoints = $givePoints;

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

    /**
     * @return Collection|GoodsSnapshot[]
     */
    public function getGoodsSnapshot(): Collection
    {
        return $this->goodsSnapshot;
    }

    public function addGoodsSnapshot(GoodsSnapshot $goodsSnapshot): self
    {
        if (!$this->goodsSnapshot->contains($goodsSnapshot)) {
            $this->goodsSnapshot[] = $goodsSnapshot;
            $goodsSnapshot->setTrade($this);
        }

        return $this;
    }

    public function removeGoodsSnapshot(GoodsSnapshot $goodsSnapshot): self
    {
        if ($this->goodsSnapshot->contains($goodsSnapshot)) {
            $this->goodsSnapshot->removeElement($goodsSnapshot);
            // set the owning side to null (unless already changed)
            if ($goodsSnapshot->getTrade() === $this) {
                $goodsSnapshot->setTrade(null);
            }
        }

        return $this;
    }

    public function getPayLog(): ?PayLog
    {
        return $this->payLog;
    }

    public function setPayLog(PayLog $payLog): self
    {
        $this->payLog = $payLog;

        return $this;
    }

    public function getFirstImage()
    {
        $goods = $this->getGoodsSnapshot()->first();
        assert($goods instanceof GoodsSnapshot);
        return $goods->getGoodsImg();
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setCreateTime(\DateTimeInterface $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getLogisticsStatus(): ?string
    {
        return $this->logisticsStatus;
    }

    public function setLogisticsStatus(?string $logisticsStatus): self
    {
        $this->logisticsStatus = $logisticsStatus;

        return $this;
    }
}
