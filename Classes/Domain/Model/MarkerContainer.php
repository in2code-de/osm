<?php
declare(strict_types=1);
namespace In2code\Osm\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class MarkerContainer
 */
class MarkerContainer
{
    /**
     * @var Marker[]
     */
    protected $markers = [];

    /**
     * MarkerContainer constructor.
     * @param array $markers
     */
    public function __construct(array $markers = [])
    {
        foreach ($markers as $markerProperties) {
            /** @var Marker $marker */
            $marker = GeneralUtility::makeInstance(Marker::class);
            $marker
                ->setTitle($markerProperties['markertitle'])
                ->setDescription($markerProperties['markerdescription'])
                ->setLatitude($markerProperties['latitude'])
                ->setLongitude($markerProperties['longitude']);
            $this->addMarker($marker);
        }
    }

    /**
     * @return Marker[]
     */
    public function getMarkers(): array
    {
        return $this->markers;
    }

    /**
     * @return array
     */
    public function getMarkerProperties(): array
    {
        $properties = [];
        foreach ($this->getMarkers() as $marker) {
            $properties[] = $marker->getProperties();
        }
        return $properties;
    }

    /**
     * @param Marker[] $markers
     * @return MarkerContainer
     */
    public function setMarkers(array $markers): self
    {
        $this->markers = $markers;
        return $this;
    }

    /**
     * @param Marker $marker
     * @return $this
     */
    public function addMarker(Marker $marker): self
    {
        $this->markers[] = $marker;
        return $this;
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return json_encode(['markers' => $this->getMarkerProperties()]);
    }
}
