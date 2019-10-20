<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 04/06/19
 * Time: 01:23
 */

namespace App\Service;

use Phpml\Classification\KNearestNeighbors;
use Phpml\Dataset\CsvDataset;

class SuspectService
{
    const DATASET_DIR = 'data/datasets/';

    private $filename = 'suspect-access.csv';

    public function trainDataset()
    {
        if (file_exists(self::DATASET_DIR . $this->filename)) {
            $dataset = new CsvDataset(self::DATASET_DIR . $this->filename, 4, false, ',');

            foreach (range(1, 10) as $k) {
                $correct = 0;
                foreach ($dataset->getSamples() as $index => $sample) {
                    $estimator = new KNearestNeighbors($k);
                    $estimator->train($other = $this->removeIndex($index, $dataset->getSamples()), $this->removeIndex($index, $dataset->getTargets()));

                    $predict = $estimator->predict([$sample]);

                    if ($predict == $dataset->getTargets()[$index]) {
                        $correct++;
                    }
                }

                echo sprintf('Accuracy (k=%s): %.02f%% correct: %s', $k, ($correct /count($dataset->getSamples())) * 100, $correct) . PHP_EOL;
            }
        }
    }

    public function removeIndex($index, $array): array
    {
        unset($array[$index]);
        return $array;
    }
}