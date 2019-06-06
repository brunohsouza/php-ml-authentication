<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 02/06/19
 * Time: 15:05
 */

namespace App\Service;

use App\Entity\LastAccess;
use Doctrine\ORM\EntityManager;
use Phpml\Dataset\CsvDataset;

class AccessService
{
    const DATASET_DIR = 'data/datasets/';

    private $filename = 'visitor-interests.csv';

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function loadDataset()
    {
        if (!file_exists(self::DATASET_DIR . $this->filename)) {
            throw new \Exception('File not Found!');
        }

        $countryIp = new CountryIpService($this->em);

        try {
            if (file_exists(self::DATASET_DIR . $this->filename)) {
                $dataset = new CsvDataset(self::DATASET_DIR . $this->filename, 4, true, ',');
                foreach (range(1, 10) as $k) {
                    foreach ($dataset->getSamples() as $index => $sample) {
                        $lastAccess = new LastAccess();
                        $country = $countryIp->findCountryByIp($sample[0]);
                        $lastAccess->setIp($sample[0]);
                        $lastAccess->setBrowser($sample[1]);
                        $lastAccess->setCountry($sample[2]);
                        $lastAccess->setLatitude($country['latitude']);
                        $lastAccess->setLongitude($country['longitude']);
                        $lastAccess->setUser(rand(1, 57354));
                        $this->em->persist($lastAccess);
                        $this->em->flush();
                    }
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

    }

    public function generateDatasetAccess()
    {
        if (!file_exists(self::DATASET_DIR . $this->filename)) {
            throw new \Exception('File not Found!');
        }

        try {
            if (file_exists(self::DATASET_DIR . $this->filename)) {
                $dataset = new CsvDataset(self::DATASET_DIR . $this->filename, 4, true, ',');
                $countryIp = new CountryIpService($this->em);
                $lines = [];
                foreach (range(1, 10) as $k) {
                    $correct = 0;
                    foreach ($dataset->getSamples() as $indice => $sample) {
                        $country = $countryIp->findCountryByIp($sample[0]);
                        $lines[] = sprintf('%s,%s,%s,%s,%s,%s,', $sample[0],$sample[1],$sample[2], $country['location']['lat'], $country['location']['lng'], rand(1, 57354)) . PHP_EOL;
                        file_put_contents('data/datasets/access.csv', $lines);
                    }
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function getUserAccess()
    {
        if (!file_exists(self::DATASET_DIR . 'access.csv')) {
            throw new \Exception('File not Found!');
        }

        try {
            $dataset = new CsvDataset(self::DATASET_DIR . 'access.csv', 6, false, ',');
            $lastAccess = $this->em->getRepository(LastAccess::class);
            $vicenty = new VicentyService();
            $lines = [];
            foreach ($dataset->getTargets() as $indice => $target) {
                if ($target) {
                    $access = $lastAccess->findOneBy(['user' => $target]);
                    if ($access instanceof LastAccess) {
                        foreach($dataset->getSamples() as $key => $sample) {
                            if ($access->getIp() == $sample[0] && $access->getUser() !== $target) {
                                $arrFrom = [$access->getLatitude(), $access->getLongitude()];
                                $arrTo = [$sample[4], $sample[5]];
                                $diffDistance = $vicenty->distance($arrFrom, $arrTo);
                                if ($diffDistance > 0) {
                                    $lines[] = sprintf('%s,%s,%s,%s,%s', $arrFrom['lat'], $arrFrom['lon'], $arrTo['lat'], $arrTo['lon'], $diffDistance) . PHP_EOL;
                                }
                            }
                        }
                    }
                    file_put_contents('data/datasets/suspect-access.csv', $lines);
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}