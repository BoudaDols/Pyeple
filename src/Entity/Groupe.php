<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe", uniqueConstraints={@ORM\UniqueConstraint(name="idGroup", columns={"idGroup"})})
 * @ORM\Entity
 */
class Groupe
{
    /**
     * @var int
     *
     * @ORM\Column(name="idGroup", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idgroup;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=45, nullable=false, options={"default"="Nouveau Groupe"})
     */
    private $nom = 'Nouveau Groupe';

    /**
     * @var string|null
     *
     * @ORM\Column(name="descriptionGrp", type="string", length=500, nullable=true)
     */
    private $descriptiongrp;

    /**
     * @var bool
     *
     * @ORM\Column(name="droit", type="boolean", nullable=false, options={"default"="1"})
     */
    private $droit = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="visibiliteGrp", type="boolean", nullable=false, options={"default"="1"})
     */
    private $visibilitegrp = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="creatorREF", type="integer", nullable=false)
     */
    private $creatorref;

    public function getIdgroup(): ?int
    {
        return $this->idgroup;
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

    public function getDescriptiongrp(): ?string
    {
        return $this->descriptiongrp;
    }

    public function setDescriptiongrp(?string $descriptiongrp): self
    {
        $this->descriptiongrp = $descriptiongrp;

        return $this;
    }

    public function getDroit(): ?bool
    {
        return $this->droit;
    }

    public function setDroit(bool $droit): self
    {
        $this->droit = $droit;

        return $this;
    }

    public function getVisibilitegrp(): ?bool
    {
        return $this->visibilitegrp;
    }

    public function setVisibilitegrp(bool $visibilitegrp): self
    {
        $this->visibilitegrp = $visibilitegrp;

        return $this;
    }

    public function getCreatorref(): ?int
    {
        return $this->creatorref;
    }

    public function setCreatorref(int $creatorref): self
    {
        $this->creatorref = $creatorref;

        return $this;
    }


}
