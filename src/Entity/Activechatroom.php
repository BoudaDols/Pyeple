<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activechatroom
 *
 * @ORM\Table(name="activechatroom")
 * @ORM\Entity
 */
class Activechatroom
{
    /**
     * @var int
     *
     * @ORM\Column(name="idChatRoom", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idchatroom;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    public function getIdchatroom(): ?int
    {
        return $this->idchatroom;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


}
