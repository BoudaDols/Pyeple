<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message", uniqueConstraints={@ORM\UniqueConstraint(name="idMessage", columns={"idMessage"})}, indexes={@ORM\Index(name="senderREF", columns={"senderREF"}), @ORM\Index(name="receiverREF", columns={"receiverREF"})})
 * @ORM\Entity
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="idMessage", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmessage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="string", length=2000, nullable=true)
     */
    private $message;

    /**
     * @var array
     *
     * @ORM\Column(name="type", type="simple_array", length=0, nullable=false, options={"default"="texte"})
     */
    private $type = 'texte';

    /**
     * @var string|null
     *
     * @ORM\Column(name="duration", type="string", length=15, nullable=true)
     */
    private $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=false)
     */
    private $time;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pieceJointe", type="string", length=200, nullable=true)
     */
    private $piecejointe;

    /**
     * @var int
     *
     * @ORM\Column(name="senderREF", type="integer", nullable=false)
     */
    private $senderref;

    /**
     * @var int
     *
     * @ORM\Column(name="receiverREF", type="integer", nullable=false)
     */
    private $receiverref;

    public function getIdmessage(): ?int
    {
        return $this->idmessage;
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

    public function getType(): ?array
    {
        return $this->type;
    }

    public function setType(array $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPiecejointe(): ?string
    {
        return $this->piecejointe;
    }

    public function setPiecejointe(?string $piecejointe): self
    {
        $this->piecejointe = $piecejointe;

        return $this;
    }

    public function getSenderref(): ?int
    {
        return $this->senderref;
    }

    public function setSenderref(int $senderref): self
    {
        $this->senderref = $senderref;

        return $this;
    }

    public function getReceiverref(): ?int
    {
        return $this->receiverref;
    }

    public function setReceiverref(int $receiverref): self
    {
        $this->receiverref = $receiverref;

        return $this;
    }


}
