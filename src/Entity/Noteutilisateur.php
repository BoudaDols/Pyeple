<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Noteutilisateur
 *
 * @ORM\Table(name="noteutilisateur")
 * @ORM\Entity
 */
class Noteutilisateur
{
    /**
     * @var int
     *
     * @ORM\Column(name="idNote", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idnote;

    /**
     * @var array
     *
     * @ORM\Column(name="typeNote", type="simple_array", length=0, nullable=false)
     */
    private $typenote;

    /**
     * @var float|null
     *
     * @ORM\Column(name="valeurNote", type="float", precision=10, scale=0, nullable=true)
     */
    private $valeurnote;

    /**
     * @var int
     *
     * @ORM\Column(name="utilisateurREF", type="integer", nullable=false)
     */
    private $utilisateurref;

    public function getIdnote(): ?int
    {
        return $this->idnote;
    }

    public function getTypenote(): ?array
    {
        return $this->typenote;
    }

    public function setTypenote(array $typenote): self
    {
        $this->typenote = $typenote;

        return $this;
    }

    public function getValeurnote(): ?float
    {
        return $this->valeurnote;
    }

    public function setValeurnote(?float $valeurnote): self
    {
        $this->valeurnote = $valeurnote;

        return $this;
    }

    public function getUtilisateurref(): ?int
    {
        return $this->utilisateurref;
    }

    public function setUtilisateurref(int $utilisateurref): self
    {
        $this->utilisateurref = $utilisateurref;

        return $this;
    }


}
