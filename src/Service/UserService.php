<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 02/06/19
 * Time: 00:55
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Dataset\CsvDataset;

class UserService
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function loadDataset()
    {
        try {
            if (file_exists('data/datasets/NationalNames.csv')) {
                $dataset = new CsvDataset('data/datasets/NationalNames.csv', 4, true, ',');

                foreach (range(1, 10) as $k) {
                    foreach ($dataset->getSamples() as $index => $sample) {
                        $user = new User($sample[1], $sample[3], $sample[2]);
                        $this->em->persist($user);
                        $this->em->flush();
                    }
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function login()
    {

    }


                        /*$estimator =  new KNearestNeighbors($k);
                        $estimator->train($other = $this->removeIndex($index, $dataset->getSamples()), $this->removeIndex($index, $dataset->getTargets()));

                        $predict = $estimator->predict([$sample]);

                        if ($predict[0] === $dataset->getTargets()[$index]) {
                            $correct++;
                        }
                    }

//                    return [$k, ($correct / count($dataset->getSamples())) * 100, $correct];
                }
            }
    }

    function removeIndex($index, $array): array
    {
        unset($array[$index]);
        return $array;
    }*/

}