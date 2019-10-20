<?php


namespace App\Controller;

use App\Service\MapService;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Overlays\Polygon;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Ivory\GoogleMap\Helper\MapHelper;

class MapController extends AbstractController
{

    const DATASET_DIR = 'data/datasets/';

    private $filename = 'access.csv';

    private $map;

    /**
     * @var MapService
     */
    private $mapService;

    public function __construct()
    {
        $this->map = new Map();
        $this->mapService = new MapService();
    }

    /**
     * @Route("/map")
     */
    public function map()
    {
        $coordenates = $this->mapService->getCoordenates();

        $this->buildMap();

        $this->buildPolygon($coordenates);

        $mapHelper = new MapHelper();
        echo $mapHelper->renderHtmlContainer($this->map);
        echo $mapHelper->renderJavascripts($this->map);
        echo $mapHelper->renderStylesheets($this->map);
        die;
    }

    public function buildMap()
    {
        $this->map->setHtmlContainerId('map-canvas');

        $this->map->setStylesheetOption('width', '1400px');
        $this->map->setStylesheetOption('height', '800px');
        $this->map->setStylesheetOption('margin', 'auto');

        $this->map->setPrefixJavascriptVariable('map_');
        $this->map->setHtmlContainerId('map_canvas');

        $this->map->setAsync(false);
        $this->map->setAutoZoom(false);

        $this->map->setCenter(0, 0, true);
        $this->map->setMapOption('zoom', 3);

        $this->map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

        $this->map->setMapOption('mapTypeId', MapTypeId::ROADMAP);
        $this->map->setMapOption('mapTypeId', 'roadmap');

        $this->map->setMapOption('disableDefaultUI', true);
        $this->map->setMapOption('disableDoubleClickZoom', true);
        $this->map->setMapOptions(array(
            'disableDefaultUI'       => true,
            'disableDoubleClickZoom' => true,
        ));
    }

    public function buildPolygon(array $coordenates)
    {
        if (is_array($coordenates) && !empty($coordenates)){
            foreach ($coordenates as $key => $local) {
                $polygon = new Polygon();

                // Configure your polygon options
                $polygon->setPrefixJavascriptVariable('polygon_');

                $polygon->setOption('fillColor', '#FF0000');
                $polygon->setOption('fillOpacity', 0.5);

                if ($key%2 == 0) {
                    $polygon->addCoordinate($local[0], $local[1], true);
                    $polygon->addCoordinate($local[2], $local[3], true);
                }
                $this->map->addPolygon($polygon);
            }
        }
    }

}
