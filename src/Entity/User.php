<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 02/06/19
 * Time: 00:26
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package PhpmlAuth\Document
 * @ORM\Table("mlauth.user")
 * @ORM\Entity
 */
class User
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
    protected $name;

    /**
     * User constructor.
     * @param $name
     * @param $gender
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}