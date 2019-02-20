<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PointsConfigRepository")
 */
class PointsConfig
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $sharePoint;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $derectPoint;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $inderectPoint;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $finderectPoint;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $payPoint;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $givePoint;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $exchangePoint;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $exCondition;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $teamCondition;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $mbInterest;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $teamInterest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSharePoint()
    {
        return $this->sharePoint;
    }

    public function setSharePoint($sharePoint): self
    {
        $this->sharePoint = $sharePoint;

        return $this;
    }

    public function getDerectPoint()
    {
        return $this->derectPoint;
    }

    public function setDerectPoint($derectPoint): self
    {
        $this->derectPoint = $derectPoint;

        return $this;
    }

    public function getInderectPoint()
    {
        return $this->inderectPoint;
    }

    public function setInderectPoint($inderectPoint): self
    {
        $this->inderectPoint = $inderectPoint;

        return $this;
    }

    public function getFinderectPoint()
    {
        return $this->finderectPoint;
    }

    public function setFinderectPoint($finderectPoint): self
    {
        $this->finderectPoint = $finderectPoint;

        return $this;
    }

    public function getPayPoint()
    {
        return $this->payPoint;
    }

    public function setPayPoint($payPoint): self
    {
        $this->payPoint = $payPoint;

        return $this;
    }

    public function getGivePoint()
    {
        return $this->givePoint;
    }

    public function setGivePoint($givePoint): self
    {
        $this->givePoint = $givePoint;

        return $this;
    }

    public function getExchangePoint()
    {
        return $this->exchangePoint;
    }

    public function setExchangePoint($exchangePoint): self
    {
        $this->exchangePoint = $exchangePoint;

        return $this;
    }

    public function getExCondition()
    {
        return $this->exCondition;
    }

    public function setExCondition($exCondition): self
    {
        $this->exCondition = $exCondition;

        return $this;
    }

    public function getTeamCondition()
    {
        return $this->teamCondition;
    }

    public function setTeamCondition($teamCondition): self
    {
        $this->teamCondition = $teamCondition;

        return $this;
    }

    public function getMbInterest()
    {
        return $this->mbInterest;
    }

    public function setMbInterest($mbInterest): self
    {
        $this->mbInterest = $mbInterest;

        return $this;
    }

    public function getTeamInterest()
    {
        return $this->teamInterest;
    }

    public function setTeamInterest($teamInterest): self
    {
        $this->teamInterest = $teamInterest;

        return $this;
    }
}
