<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userhasgroupe
 *
 * @ORM\Table(name="userhasgroupe")
 * @ORM\Entity
 */
class Userhasgroupe
{
    /**
     * @var int
     *
     * @ORM\Column(name="idUserHasGroupe", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iduserhasgroupe;

    /**
     * @var int
     *
     * @ORM\Column(name="userREF", type="integer", nullable=false)
     */
    private $userref;

    /**
     * @var int
     *
     * @ORM\Column(name="groupeREF", type="integer", nullable=false)
     */
    private $grouperef;

    /**
     * @var array
     *
     * @ORM\Column(name="droitUser", type="simple_array", length=0, nullable=false, options={"default"="member"})
     */
    private $droituser = 'member';

    public function getIduserhasgroupe(): ?int
    {
        return $this->iduserhasgroupe;
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

    public function getGrouperef(): ?int
    {
        return $this->grouperef;
    }

    public function setGrouperef(int $grouperef): self
    {
        $this->grouperef = $grouperef;

        return $this;
    }

    public function getDroituser(): ?array
    {
        return $this->droituser;
    }

    public function setDroituser(array $droituser): self
    {
        $this->droituser = $droituser;

        return $this;
    }


}
