<?php
/**
 * Created by PhpStorm.
 * User: brunosouza
 * Date: 04/06/19
 * Time: 00:07
 */

namespace App\Service;

use Phpml\Math\Distance;

class VicentyService implements Distance
{

    const EARTH_RADIUS = 6371000;

    /**
     * Calculates the great-circle distance between two points, with the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function distance(array $coordFrom, array $coordTo): float
    {
        $latFrom = deg2rad(str_replace('.', '', $coordFrom[0]));
        $lonFrom = deg2rad(str_replace('.', '', $coordFrom[1]));
        $latTo = deg2rad(str_replace('.', '', $coordTo[0]));
        $lonTo = deg2rad(str_replace('.', '', $coordTo[1]));

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        return $angle * self::EARTH_RADIUS;
    }
}