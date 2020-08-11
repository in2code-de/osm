<?php
declare(strict_types=1);
namespace In2code\Osm\Controller;

use In2code\Osm\Domain\Service\GeoConverter;
use In2code\Osm\Domain\Service\Markers;
use In2code\Osm\Exception\RequestFailedException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class MapController
 */
class MapController extends ActionController
{
    /**
     * @var GeoConverter
     */
    protected $geoConverter = null;

    /**
     * @var Markers
     */
    protected $markers = null;

    /**
     * MapController constructor.
     * @param GeoConverter $geoConverter
     */
    public function __construct(GeoConverter $geoConverter, Markers $markers)
    {
        $this->geoConverter = $geoConverter;
        $this->markers = $markers;
    }

    /**
     * @return void
     * @throws RequestFailedException
     */
    public function singleAddressAction(): void
    {
        $result = $this->geoConverter->convertAddressToCoordinates($this->settings['address']);
        $this->view->assignMultiple([
            'latitude' => $result[0],
            'longitude' => $result[1],
            'data' => $this->configurationManager->getContentObject()->data
        ]);
    }

    /**
     * @param int $contentIdentifier
     * @return string
     * @throws RequestFailedException
     */
    public function getMarkersAction(int $contentIdentifier): string
    {
        $markers = $this->markers->getMarkers($contentIdentifier);
        return json_encode(['markers' => $markers]);
    }
}
