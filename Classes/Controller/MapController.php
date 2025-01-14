<?php
declare(strict_types=1);
namespace In2code\Osm\Controller;

use Doctrine\DBAL\Exception as ExceptionDbal;
use Psr\Http\Message\ResponseInterface;
use In2code\Osm\Domain\Service\GeoConverter;
use In2code\Osm\Domain\Service\Markers;
use In2code\Osm\Exception\ConfigurationMissingException;
use In2code\Osm\Exception\RequestFailedException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MapController extends ActionController
{
    public function __construct(protected ?GeoConverter $geoConverter, protected ?Markers $markers)
    {
    }

    public function initializeView($view): void
    {
        $this->view->assignMultiple([
            'configurationExists' => ($this->settings['configurationExists'] ?? '') === '1',
        ]);
    }

    public function plugin1Action(): ResponseInterface
    {
        $this->view->assignMultiple([
            'data' => $this->request->getAttribute('currentContentObject')->data
        ]);
        return $this->htmlResponse();
    }

    public function plugin2Action(): ResponseInterface
    {
        $this->view->assignMultiple([
            'data' => $this->request->getAttribute('currentContentObject')->data
        ]);
        return $this->htmlResponse();
    }

    /**
     * Called from AJAX to get a list of markers to insert
     *
     * @throws RequestFailedException
     * @throws ConfigurationMissingException
     * @throws ExceptionDbal
     */
    public function getMarkersAction(int $contentIdentifier): ResponseInterface
    {
        $markerContainer = $this->markers->getMarkers($contentIdentifier);
        return $this->jsonResponse($markerContainer->getJson());
    }
}
