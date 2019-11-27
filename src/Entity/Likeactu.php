<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likeactu
 *
 * @ORM\Table(name="likeactu")
 * @ORM\Entity
 */
class Likeactu
{
    /**
     * @var int
     *
     * @ORM\Column(name="idLikeActu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlikeactu;

    /**
     * @var int
     *
     * @ORM\Column(name="userREF", type="integer", nullable=false)
     */
    private $userref;

    /**
     * @var int
     *
     * @ORM\Column(name="actuREF", type="integer", nullable=false)
     */
    private $acturef;

    public function getIdlikeactu(): ?int
    {
        return $this->idlikeactu;
    }

    public function getUserref(): ?int
    {
        return $this->userref;
    }

    public function setUserref(int $userref): self
    {
        $this->userref = $userref;

        return $this;
    }

    public function getActuref(): ?int
    {
        return $this->acturef;
    }

    public function setActuref(int $acturef): self
    {
        $this->acturef = $acturef;

        return $this;
    }


}
