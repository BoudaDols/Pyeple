<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sessionutilisateur
 *
 * @ORM\Table(name="sessionutilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="utilisateurREF", columns={"utilisateurREF"}), @ORM\UniqueConstraint(name="cleToken", columns={"cleToken"})})
 * @ORM\Entity
 */
class Sessionutilisateur
{
    /**
     * @var int
     *
     * @ORM\Column(name="idSessionUtilisateur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idsessionutilisateur;

    /**
     * @var string
     *
     * @ORM\Column(name="cleToken", type="string", length=500, nullable=false)
     */
    private $cletoken;

    /**
     * @var int
     *
     * @ORM\Column(name="utilisateurREF", type="integer", nullable=false)
     */
    private $utilisateurref;

    /**
     * @var int
     *
     * @ORM\Column(name="dureeBail", type="integer", nullable=false, options={"default"="30"})
     */
    private $dureebail = '30';

    public function getIdsessionutilisateur(): ?int
    {
        return $this->idsessionutilisateur;
    }

    public function getCletoken(): ?string
    {
        return $this->cletoken;
    }

    public function setCletoken(string $cletoken): self
    {
        $this->cletoken = $cletoken;

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

    public function getDureebail(): ?int
    {
        return $this->dureebail;
    }

    public function setDureebail(int $dureebail): self
    {
        $this->dureebail = $dureebail;

        return $this;
    }


}
