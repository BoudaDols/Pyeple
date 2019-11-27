<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement")
 * @ORM\Entity
 */
class Abonnement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idAbonnement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idabonnement;

    /**
     * @var int
     *
     * @ORM\Column(name="abonneREF", type="integer", nullable=false)
     */
    private $abonneref;

    /**
     * @var int
     *
     * @ORM\Column(name="hashtagREF", type="integer", nullable=false)
     */
    private $hashtagref;

    public function getIdabonnement(): ?int
    {
        return $this->idabonnement;
    }

    public function getAbonneref(): ?int
    {
        return $this->abonneref;
    }

    public function setAbonneref(int $abonneref): self
    {
        $this->abonneref = $abonneref;

        return $this;
    }

    public function getHashtagref(): ?int
    {
        return $this->hashtagref;
    }

    public function setHashtagref(int $hashtagref): self
    {
        $this->hashtagref = $hashtagref;

        return $this;
    }


}
