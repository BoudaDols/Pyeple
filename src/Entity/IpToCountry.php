<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IpToCountry
 *
 * @ORM\Table(name="ip-to-country")
 * @ORM\Entity
 */
class IpToCountry
{
    /**
     * @var float
     *
     * @ORM\Column(name="IP_FROM", type="float", precision=10, scale=0, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $ipFrom;

    /**
     * @var float
     *
     * @ORM\Column(name="IP_TO", type="float", precision=10, scale=0, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $ipTo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="COUNTRY_CODE", type="string", length=2, nullable=true, options={"fixed"=true})
     */
    private $countryCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="COUNTRY_NAME", type="string", length=50, nullable=true)
     */
    private $countryName;

    public function getIpFrom(): ?float
    {
        return $this->ipFrom;
    }

    public function getIpTo(): ?float
    {
        return $this->ipTo;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(?string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }


}
