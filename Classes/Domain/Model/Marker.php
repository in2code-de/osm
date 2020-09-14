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
     * @var string
     */
    protected $icon = '';

    /**
     * @var int
     */
    protected $iconWidth = 0;

    /**
     * @var int
     */
    protected $iconHeight = 0;

    /**
     * @var int|null
     */
    protected $iconOffsetX = null;

    /**
     * @var int|null
     */
    protected $iconOffsetY = null;

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
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return Marker
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return int
     */
    public function getIconWidth(): int
    {
        return $this->iconWidth;
    }

    /**
     * @param int $iconWidth
     * @return Marker
     */
    public function setIconWidth(int $iconWidth): self
    {
        $this->iconWidth = $iconWidth;
        return $this;
    }

    /**
     * @return int
     */
    public function getIconHeight(): int
    {
        return $this->iconHeight;
    }

    /**
     * @param int $iconHeight
     * @return Marker
     */
    public function setIconHeight(int $iconHeight): self
    {
        $this->iconHeight = $iconHeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getIconOffsetX(): ?int
    {
        return $this->iconOffsetX;
    }

    /**
     * @param int $iconOffsetX
     * @return Marker
     */
    public function setIconOffsetX(int $iconOffsetX): self
    {
        $this->iconOffsetX = $iconOffsetX;
        return $this;
    }

    /**
     * @return int
     */
    public function getIconOffsetY(): ?int
    {
        return $this->iconOffsetY;
    }

    /**
     * @param int $iconOffsetY
     * @return Marker
     */
    public function setIconOffsetY(int $iconOffsetY): self
    {
        $this->iconOffsetY = $iconOffsetY;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        $properties = [
            'markertitle' => $this->getTitle(),
            'markerdescription' => $this->getDescription(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
        if ($this->getIcon() !== '') {
            $properties['icon'] = $this->getIcon();
        }
        if ($this->getIconWidth() > 0) {
            $properties['iconWidth'] = $this->getIconWidth();
        }
        if ($this->getIconHeight() > 0) {
            $properties['iconHeight'] = $this->getIconHeight();
        }
        if ($this->getIconOffsetX() !== null) {
            $properties['iconOffsetX'] = $this->getIconOffsetX();
        }
        if ($this->getIconOffsetY() !== null) {
            $properties['iconOffsetY'] = $this->getIconOffsetY();
        }
        return $properties;
    }
}
