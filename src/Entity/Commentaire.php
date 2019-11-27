<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="idComment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcomment;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=500, nullable=false)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="numLike", type="integer", nullable=false)
     */
    private $numlike;

    /**
     * @var int
     *
     * @ORM\Column(name="numComm", type="integer", nullable=false)
     */
    private $numcomm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timePublish", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $timepublish = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="commOrder", type="integer", nullable=false, options={"default"="1"})
     */
    private $commorder = '1';

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

    public function getIdcomment(): ?int
    {
        return $this->idcomment;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getNumlike(): ?int
    {
        return $this->numlike;
    }

    public function setNumlike(int $numlike): self
    {
        $this->numlike = $numlike;

        return $this;
    }

    public function getNumcomm(): ?int
    {
        return $this->numcomm;
    }

    public function setNumcomm(int $numcomm): self
    {
        $this->numcomm = $numcomm;

        return $this;
    }

    public function getTimepublish(): ?\DateTimeInterface
    {
        return $this->timepublish;
    }

    public function setTimepublish(\DateTimeInterface $timepublish): self
    {
        $this->timepublish = $timepublish;

        return $this;
    }

    public function getCommorder(): ?int
    {
        return $this->commorder;
    }

    public function setCommorder(int $commorder): self
    {
        $this->commorder = $commorder;

        return $this;
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
