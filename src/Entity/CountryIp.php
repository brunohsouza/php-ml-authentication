<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 02/06/19
 * Time: 17:50
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CountryIpRepository")
 * @ORM\Table(name="ip2location")
 */
class CountryIp
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $ipFrom;

    /**
     * @ORM\Column(type="integer")
     */
    private $ipTo;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $countryCode;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $countryName;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $longitude;

    /**
     * @return mixed
     */
    public function getIpFrom()
    {
        return $this->ipFrom;
    }

    /**
     * @param mixed $ipFrom
     */
    public function setIpFrom($ipFrom): void
    {
        $this->ipFrom = $ipFrom;
    }

    /**
     * @return mixed
     */
    public function getIpTo()
    {
        return $this->ipTo;
    }

    /**
     * @param mixed $ipTo
     */
    public function setIpTo($ipTo): void
    {
        $this->ipTo = $ipTo;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode($countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return mixed
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param mixed $countryName
     */
    public function setCountryName($countryName): void
    {
        $this->countryName = $countryName;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }
}