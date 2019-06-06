<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 04/06/19
 * Time: 02:19
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessController extends AbstractController
{
    /**
     * @Route("/access")
     */
    public function getAccess()
    {
        $file = getenv('DATASET_DIR') . '/' . 'suspect-access.csv';
        $row = 1;
        $access = [];
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                for ($i=0;$i<$num;$i++) {
                    for ($j=0;$j<5;$j++) {
                       $access[$row]['latFrom'] = $data[0];
                       $access[$row]['lonFrom'] = $data[1];
                       $access[$row]['latTo'] = $data[2];
                       $access[$row]['lonTo'] = $data[3];
                       $access[$row]['diff'] = $data[4];
                    }
                }
                $row++;
            }
            fclose($handle);
        }
        return new Response(json_encode($access));
    }
}