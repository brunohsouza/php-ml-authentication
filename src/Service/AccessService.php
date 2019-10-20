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

class AccessService extends AbstractService
{
    const DATASET_DIR = 'data/datasets/';

    private $filename = 'visitor-interests.csv';

    /**
     * @var UserService
     */
    private $userService;

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->userService = new UserService($this->em);
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
                $datasetNames = $this->userService->loadDataset();
                $countryIp = new CountryIpService($this->em);
                $lines = [];
                foreach ($dataset->getSamples() as $indice => $sample) {
                    $country = json_decode($countryIp->findIpOnAPI($sample[0]), true);
                    $idPerson = rand(1, 57354);
                    $accessDevice = $this->removeComma($sample[1]);
                    $lines[] = sprintf('%s,%s,%s,%s,%s,%s,%s,',
                            $datasetNames[$idPerson][0],
                            $datasetNames[$idPerson][1],
                            $sample[0],
                            $accessDevice,
                            $sample[2],
                            $country['location']['lat'],
                            $country['location']['lng'])
                        . PHP_EOL;
                    file_put_contents('data/datasets/access.csv', $lines);
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
                            if ($access->getUser() !== $target) {
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