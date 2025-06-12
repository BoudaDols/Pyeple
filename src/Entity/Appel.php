<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appel
 *
 * @ORM\Table(name="appel", indexes={@ORM\Index(name="callerREF", columns={"callerREF"}), @ORM\Index(name="receiverREF", columns={"receiverREF"})})
 * @ORM\Entity
 */
class Appel
{
    /**
     * @var int
     *
     * @ORM\Column(name="idAppel", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idappel;

    /**
     * @var string
     *
     * @ORM\Column(name="callerREF", type="string", length=45, nullable=false)
     */
    private $callerref;

    /**
     * @var string
     *
     * @ORM\Column(name="receiverREF", type="string", length=45, nullable=false)
     */
    private $receiverref;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $time = 'CURRENT_TIMESTAMP';

    /**
     * @var array
     *
     * @ORM\Column(name="categorie", type="simple_array", length=0, nullable=false)
     */
    private $categorie;

    /**
     * @var array
     *
     * @ORM\Column(name="type", type="simple_array", length=0, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=45, nullable=false)
     */
    private $duree;

    public function getIdappel(): ?int
    {
        return $this->idappel;
    }

    public function getCallerref(): ?string
    {
        return $this->callerref;
    }

    public function setCallerref(string $callerref): self
    {
        $this->callerref = $callerref;

        return $this;
    }

    public function getReceiverref(): ?string
    {
        return $this->receiverref;
    }

    public function setReceiverref(string $receiverref): self
    {
        $this->receiverref = $receiverref;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getCategorie(): ?array
    {
        return $this->categorie;
    }

    public function setCategorie(array $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getType(): ?array
    {
        return $this->type;
    }

    public function setType(array $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }


}
