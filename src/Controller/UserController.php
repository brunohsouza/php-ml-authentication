<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 02/06/19
 * Time: 03:46
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\UserService;
use Symfony\Component\DependencyInjection\Container;

class UserController extends AbstractController
{
    private $userService;

    protected $container;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->container = new Container();
    }

    /**
     * @Route("/load")
     * @throws \Exception
     */
    public function loadUsers()
    {
        $this->userService->loadDataset();
    }
}