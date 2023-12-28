<?php
declare(strict_types=1);
namespace In2code\Osm\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MarkerContainer
{
    protected array $markers = [];

    /**
     * Configuration from FlexForm
     *
     * @var array
     */
    protected array $configuration = [];

    public function __construct(array $markers, array $configuration)
    {
        $this->configuration = $configuration;
        foreach ($markers as $markerProperties) {
            /** @var Marker $marker */
            $marker = GeneralUtility::makeInstance(Marker::class);
            $marker
                ->setMarker((int)($markerProperties['marker'] ?? 0))
                ->setTitle($markerProperties['markertitle'] ?? '')
                ->setDescription($markerProperties['markerdescription'] ?? '')
                ->setLatitude((float)$markerProperties['latitude'] ?? 0.0)
                ->setLongitude((float)$markerProperties['longitude'] ?? 0.0);
            if (!empty($markerProperties['tt_address_uid'])) {
                $marker->setAddressIdentifier((int)$markerProperties['tt_address_uid']);
            }
            $this->addMarker($marker);
        }
    }

    public function getMarkers(): array
    {
        return $this->markers;
    }

    public function getMarkerProperties(): array
    {
        $properties = [];
        foreach ($this->getMarkers() as $marker) {
            $properties[] = $marker->getProperties();
        }
        return $properties;
    }

    public function setMarkers(array $markers): self
    {
        $this->markers = $markers;
        return $this;
    }

    public function addMarker(Marker $marker): self
    {
        $this->markers[] = $marker;
        return $this;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): self
    {
        $this->configuration = $configuration;
        return $this;
    }

    public function getJson(): string
    {
        return json_encode(['markers' => $this->getMarkerProperties()]);
    }
}
