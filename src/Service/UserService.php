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
    const DATASET_DIR = 'data/datasets/';

    private $em;

    private $nationalNamesCsv = 'NationalNames.csv';

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function loadDataset()
    {
        try {
            if (file_exists(self::DATASET_DIR . $this->nationalNamesCsv)) {
                $dataset = new CsvDataset(self::DATASET_DIR . $this->nationalNamesCsv, 4, true, ',');
                foreach ($dataset->getSamples() as $index => $sample) {
                    $user = new User($sample[1]);
                    $this->em->persist($user);
                    $this->em->flush();
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function getUserNamesDataset()
    {
        try {
            $fileNames = self::DATASET_DIR . $this->nationalNamesCsv;
            if (file_exists($fileNames)) {
                $dataset = new CsvDataset($fileNames, 2, true, ',');
                $names = [];
                foreach ($dataset->getSamples() as $index => $sample) {
                    $names[] = [$sample[0], $sample[1]];
                }
                return $names;
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}