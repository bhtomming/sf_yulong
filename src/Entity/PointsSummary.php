<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PointsSummaryRepository")
 */
class PointsSummary
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $totalPoints;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $expendPoints;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $circulate;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $lastPoints;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $producePoints;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPoints()
    {
        return $this->totalPoints;
    }

    public function setTotalPoints($totalPoints): self
    {
        $this->totalPoints = $totalPoints;

        return $this;
    }

    public function getExpendPoints()
    {
        return $this->expendPoints;
    }

    public function setExpendPoints($expendPoints): self
    {
        $this->expendPoints = $expendPoints;

        return $this;
    }

    public function getCirculate()
    {
        return $this->circulate;
    }

    public function setCirculate($circulate): self
    {
        $this->circulate = $circulate;

        return $this;
    }

    public function getLastPoints()
    {
        return $this->lastPoints;
    }

    public function setLastPoints($lastPoints): self
    {
        $this->lastPoints = $lastPoints;

        return $this;
    }

    public function getProducePoints()
    {
        return $this->producePoints;
    }

    public function setProducePoints($producePoints): self
    {
        $this->producePoints = $producePoints;

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
}
