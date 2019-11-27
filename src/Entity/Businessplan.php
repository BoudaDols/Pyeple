<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Businessplan
 *
 * @ORM\Table(name="businessplan")
 * @ORM\Entity
 */
class Businessplan
{
    /**
     * @var int
     *
     * @ORM\Column(name="idBusinessPlan", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idbusinessplan;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=50, nullable=false)
     */
    private $domaine;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="benefice", type="string", length=100, nullable=false)
     */
    private $benefice;

    /**
     * @var string
     *
     * @ORM\Column(name="periode", type="string", length=15, nullable=false)
     */
    private $periode;

    /**
     * @var string
     *
     * @ORM\Column(name="marketing", type="string", length=100, nullable=false)
     */
    private $marketing;

    /**
     * @var string
     *
     * @ORM\Column(name="cible", type="string", length=100, nullable=false)
     */
    private $cible;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=2000, nullable=true)
     */
    private $image;

    /**
     * @var array
     *
     * @ORM\Column(name="etat", type="simple_array", length=0, nullable=false)
     */
    private $etat;

    /**
     * @var int
     *
     * @ORM\Column(name="quote", type="integer", nullable=false)
     */
    private $quote;

    public function getIdbusinessplan(): ?int
    {
        return $this->idbusinessplan;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

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

    public function getBenefice(): ?string
    {
        return $this->benefice;
    }

    public function setBenefice(string $benefice): self
    {
        $this->benefice = $benefice;

        return $this;
    }

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): self
    {
        $this->periode = $periode;

        return $this;
    }

    public function getMarketing(): ?string
    {
        return $this->marketing;
    }

    public function setMarketing(string $marketing): self
    {
        $this->marketing = $marketing;

        return $this;
    }

    public function getCible(): ?string
    {
        return $this->cible;
    }

    public function setCible(string $cible): self
    {
        $this->cible = $cible;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getEtat(): ?array
    {
        return $this->etat;
    }

    public function setEtat(array $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getQuote(): ?int
    {
        return $this->quote;
    }

    public function setQuote(int $quote): self
    {
        $this->quote = $quote;

        return $this;
    }


}
