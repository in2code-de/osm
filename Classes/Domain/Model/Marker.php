<?php
declare(strict_types=1);
namespace In2code\Osm\Domain\Model;

class Marker
{
    /**
     * tt_address.uid if this marker is created from such a record
     *
     * @var int
     */
    protected int $addressIdentifier = 0;

    /**
     * Should a marker be displayed?
     *
     * @var int
     */
    protected int $marker = 0;

    protected string $title = '';
    protected string $description = '';

    protected float $latitude = 0.0;
    protected float $longitude = 0.0;

    protected string $icon = '';

    protected int $iconWidth = 0;
    protected int $iconHeight = 0;

    protected ?int $iconOffsetX = null;
    protected ?int $iconOffsetY = null;

    public function getAddressIdentifier(): int
    {
        return $this->addressIdentifier;
    }

    public function setAddressIdentifier(int $addressIdentifier): self
    {
        $this->addressIdentifier = $addressIdentifier;
        return $this;
    }

    public function getMarker(): int
    {
        return $this->marker;
    }

    public function setMarker(int $marker): self
    {
        $this->marker = $marker;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getIconWidth(): int
    {
        return $this->iconWidth;
    }

    public function setIconWidth(int $iconWidth): self
    {
        $this->iconWidth = $iconWidth;
        return $this;
    }

    public function getIconHeight(): int
    {
        return $this->iconHeight;
    }

    public function setIconHeight(int $iconHeight): self
    {
        $this->iconHeight = $iconHeight;
        return $this;
    }

    public function getIconOffsetX(): ?int
    {
        return $this->iconOffsetX;
    }

    public function setIconOffsetX(int $iconOffsetX): self
    {
        $this->iconOffsetX = $iconOffsetX;
        return $this;
    }

    public function getIconOffsetY(): ?int
    {
        return $this->iconOffsetY;
    }

    public function setIconOffsetY(int $iconOffsetY): self
    {
        $this->iconOffsetY = $iconOffsetY;
        return $this;
    }

    public function getProperties(): array
    {
        $properties = [
            'marker' => $this->getMarker(),
            'markertitle' => $this->getTitle(),
            'markerdescription' => $this->getDescription(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
        if ($this->getAddressIdentifier() > 0) {
            $properties['addressIdentifier'] = $this->getAddressIdentifier();
        }
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
