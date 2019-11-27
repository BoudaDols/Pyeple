<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Retrait
 *
 * @ORM\Table(name="retrait")
 * @ORM\Entity
 */
class Retrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="idRetrait", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idretrait;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer", nullable=false)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="monnaie", type="string", length=45, nullable=false)
     */
    private $monnaie;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=100, nullable=false)
     */
    private $lieu;

    /**
     * @var int
     *
     * @ORM\Column(name="noTransaction", type="integer", nullable=false)
     */
    private $notransaction;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    public function getIdretrait(): ?int
    {
        return $this->idretrait;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getMonnaie(): ?string
    {
        return $this->monnaie;
    }

    public function setMonnaie(string $monnaie): self
    {
        $this->monnaie = $monnaie;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getNotransaction(): ?int
    {
        return $this->notransaction;
    }

    public function setNotransaction(int $notransaction): self
    {
        $this->notransaction = $notransaction;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


}
