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
     * Configuration from FlexForm
     *
     * @var array
     */
    protected $configuration = [];

    /**
     * MarkerContainer constructor.
     * @param array $markers
     * @param array $configuration
     */
    public function __construct(array $markers, array $configuration)
    {
        $this->configuration = $configuration;
        foreach ($markers as $markerProperties) {
            /** @var Marker $marker */
            $marker = GeneralUtility::makeInstance(Marker::class);
            $marker
                ->setMarker((int)$markerProperties['marker'])
                ->setTitle($markerProperties['markertitle'])
                ->setDescription($markerProperties['markerdescription'])
                ->setLatitude((float)$markerProperties['latitude'])
                ->setLongitude((float)$markerProperties['longitude']);
            if (!empty($markerProperties['tt_address_uid'])) {
                $marker->setAddressIdentifier((int)$markerProperties['tt_address_uid']);
            }
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
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     * @return MarkerContainer
     */
    public function setConfiguration(array $configuration): self
    {
        $this->configuration = $configuration;
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
