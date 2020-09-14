<?php
declare(strict_types=1);
namespace In2code\Osm\Controller;

use In2code\Osm\Domain\Service\GeoConverter;
use In2code\Osm\Domain\Service\Markers;
use In2code\Osm\Exception\ConfigurationMissingException;
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
     * @return void
     */
    public function plugin1Action(): void
    {
        $this->view->assignMultiple([
            'data' => $this->configurationManager->getContentObject()->data
        ]);
    }

    /**
     * @return void
     */
    public function plugin2Action(): void
    {
        $this->view->assignMultiple([
            'data' => $this->configurationManager->getContentObject()->data
        ]);
    }

    /**
     * Called from AJAX to get a list of markers to insert
     *
     * @param int $contentIdentifier
     * @return string
     * @throws RequestFailedException
     * @throws ConfigurationMissingException
     */
    public function getMarkersAction(int $contentIdentifier): string
    {
        $markerContainer = $this->markers->getMarkers($contentIdentifier);
        return $markerContainer->getJson();
    }
}
