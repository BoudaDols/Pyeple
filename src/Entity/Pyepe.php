<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pyepe
 *
 * @ORM\Table(name="pyepe", uniqueConstraints={@ORM\UniqueConstraint(name="idPyepe", columns={"idPyepe"})}, indexes={@ORM\Index(name="senderREF", columns={"senderREF"}), @ORM\Index(name="receiverREF", columns={"receiverREF"})})
 * @ORM\Entity
 */
class Pyepe
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPyepe", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpyepe;

    /**
     * @var string
     *
     * @ORM\Column(name="senderREF", type="string", length=45, nullable=false)
     */
    private $senderref;

    /**
     * @var string
     *
     * @ORM\Column(name="receiverREF", type="string", length=45, nullable=false)
     */
    private $receiverref;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=false)
     */
    private $time;

    public function getIdpyepe(): ?int
    {
        return $this->idpyepe;
    }

    public function getSenderref(): ?string
    {
        return $this->senderref;
    }

    public function setSenderref(string $senderref): self
    {
        $this->senderref = $senderref;

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


}
