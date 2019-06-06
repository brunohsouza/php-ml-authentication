<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 02/06/19
 * Time: 00:43
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class LastAccess
 * @package App\Document
 * @ORM\Entity
 * @ORM\Table("last_access")
 */
class LastAccess
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $ip;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $browser;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $country;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $latitude;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $longitude;

    /**
     * @var User
     * @ORM\Column(type="integer")
     */
    protected $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return LastAccess
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return LastAccess
     */
    public function setIp(string $ip): LastAccess
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getBrowser(): string
    {
        return $this->browser;
    }

    /**
     * @param string $browser
     * @return LastAccess
     */
    public function setBrowser(string $browser): LastAccess
    {
        $this->browser = $browser;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return LastAccess
     */
    public function setCountry(string $country): LastAccess
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     * @return LastAccess
     */
    public function setLatitude(string $latitude): LastAccess
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     * @return LastAccess
     */
    public function setLongitude(string $longitude): LastAccess
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return LastAccess
     */
    public function setUser($user): LastAccess
    {
        $this->user = $user;
        return $this;
    }
}