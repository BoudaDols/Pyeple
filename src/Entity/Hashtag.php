<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hashtag
 *
 * @ORM\Table(name="hashtag", uniqueConstraints={@ORM\UniqueConstraint(name="nomHashtag", columns={"nomHashtag"})})
 * @ORM\Entity
 */
class Hashtag
{
    /**
     * @var int
     *
     * @ORM\Column(name="idHashtag", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idhashtag;

    /**
     * @var string
     *
     * @ORM\Column(name="nomHashtag", type="string", length=32, nullable=false)
     */
    private $nomhashtag;

    /**
     * @var string
     *
     * @ORM\Column(name="categorieHashtag", type="string", length=20, nullable=false)
     */
    private $categoriehashtag;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionHashtgag", type="string", length=500, nullable=false)
     */
    private $descriptionhashtgag;

    /**
     * @var bool
     *
     * @ORM\Column(name="EtatHashtag", type="boolean", nullable=false, options={"default"="1"})
     */
    private $etathashtag = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeCreated", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $timecreated = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="creatorREF", type="integer", nullable=false)
     */
    private $creatorref;

    public function getIdhashtag(): ?int
    {
        return $this->idhashtag;
    }

    public function getNomhashtag(): ?string
    {
        return $this->nomhashtag;
    }

    public function setNomhashtag(string $nomhashtag): self
    {
        $this->nomhashtag = $nomhashtag;

        return $this;
    }

    public function getCategoriehashtag(): ?string
    {
        return $this->categoriehashtag;
    }

    public function setCategoriehashtag(string $categoriehashtag): self
    {
        $this->categoriehashtag = $categoriehashtag;

        return $this;
    }

    public function getDescriptionhashtgag(): ?string
    {
        return $this->descriptionhashtgag;
    }

    public function setDescriptionhashtgag(string $descriptionhashtgag): self
    {
        $this->descriptionhashtgag = $descriptionhashtgag;

        return $this;
    }

    public function getEtathashtag(): ?bool
    {
        return $this->etathashtag;
    }

    public function setEtathashtag(bool $etathashtag): self
    {
        $this->etathashtag = $etathashtag;

        return $this;
    }

    public function getTimecreated(): ?\DateTimeInterface
    {
        return $this->timecreated;
    }

    public function setTimecreated(\DateTimeInterface $timecreated): self
    {
        $this->timecreated = $timecreated;

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
