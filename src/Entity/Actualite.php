<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actualite
 *
 * @ORM\Table(name="actualite", uniqueConstraints={@ORM\UniqueConstraint(name="idActu", columns={"idActu"})})
 * @ORM\Entity
 */
class Actualite
{
    /**
     * @var int
     *
     * @ORM\Column(name="idActu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idactu;

    /**
     * @var array
     *
     * @ORM\Column(name="type", type="simple_array", length=0, nullable=false)
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="categorie", type="string", length=50, nullable=true)
     */
    private $categorie;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="string", length=100, nullable=true)
     */
    private $message;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="string", length=200, nullable=true)
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
     * @var string|null
     *
     * @ORM\Column(name="publishPlace", type="string", length=100, nullable=true)
     */
    private $publishplace;

    /**
     * @var int
     *
     * @ORM\Column(name="publisherREF", type="integer", nullable=false)
     */
    private $publisherref;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=false)
     */
    private $time;

    public function getIdactu(): ?int
    {
        return $this->idactu;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
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

    public function getPublishplace(): ?string
    {
        return $this->publishplace;
    }

    public function setPublishplace(?string $publishplace): self
    {
        $this->publishplace = $publishplace;

        return $this;
    }

    public function getPublisherref(): ?int
    {
        return $this->publisherref;
    }

    public function setPublisherref(int $publisherref): self
    {
        $this->publisherref = $publisherref;

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


}
