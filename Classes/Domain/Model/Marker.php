<?php
declare(strict_types=1);
namespace In2code\Osm\Domain\Model;

/**
 * Class Marker
 */
class Marker
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var float
     */
    protected $latitude = 0.0;

    /**
     * @var float
     */
    protected $longitude = 0.0;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Marker
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Marker
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Marker
     */
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Marker
     */
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return [
            'markertitle' => $this->getTitle(),
            'markerdescription' => $this->getDescription(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
    }
}
