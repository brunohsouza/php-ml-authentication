<?php

namespace App\Service;

use Phpml\Dataset\CsvDataset;

class MapService
{

    const DATASET_DIR = '../data/datasets/';

    private $filename = 'suspect-access.csv';

    public function getCoordenates()
    {
        if (!file_exists(self::DATASET_DIR . $this->filename)) {
            throw new \Exception('File not Found!');
        }

        try {
            $dataset = new CsvDataset(self::DATASET_DIR . $this->filename, 4, true, ',');
            $coordenates = [];
            $newSample = [];
            foreach ($dataset->getSamples() as $index => $sample) {
                foreach ($sample as $key => $item) {
                    if (!strstr($item, '.')) {
                        $item = substr($item, 0, 2) . '.' . substr($item, 2);
                    }
                    $newSample[$key] = $item;
                }
                $coordenates[$index] = $newSample;
            }
            return $coordenates;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}