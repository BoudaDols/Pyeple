<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likecomment
 *
 * @ORM\Table(name="likecomment")
 * @ORM\Entity
 */
class Likecomment
{
    /**
     * @var int
     *
     * @ORM\Column(name="idLikeComment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlikecomment;

    /**
     * @var int
     *
     * @ORM\Column(name="userREF", type="integer", nullable=false)
     */
    private $userref;

    /**
     * @var int
     *
     * @ORM\Column(name="commentREF", type="integer", nullable=false)
     */
    private $commentref;

    public function getIdlikecomment(): ?int
    {
        return $this->idlikecomment;
    }

    public function getUserref(): ?int
    {
        return $this->userref;
    }

    public function setUserref(int $userref): self
    {
        $this->userref = $userref;

        return $this;
    }

    public function getCommentref(): ?int
    {
        return $this->commentref;
    }

    public function setCommentref(int $commentref): self
    {
        $this->commentref = $commentref;

        return $this;
    }


}
