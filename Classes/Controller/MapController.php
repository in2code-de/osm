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
     * @param Markers $markers
     */
    public function __construct(GeoConverter $geoConverter, Markers $markers)
    {
        $this->geoConverter = $geoConverter;
        $this->markers = $markers;
    }

    /**
     * Show a map with a single marker in Pi1 or just show the map without marker
     *
     * @return void
     * @throws RequestFailedException
     */
    public function singleAddressAction(): void
    {
        $coordinates = $this->geoConverter->getCoordinatesFromSettings($this->settings);
        $this->view->assignMultiple([
            'latitude' => $coordinates[0],
            'longitude' => $coordinates[1],
            'data' => $this->configurationManager->getContentObject()->data
        ]);
    }

    /**
     * Show a map with a more markers in Pi2
     *
     * @return void
     */
    public function multipleAddressesAction(): void
    {
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
