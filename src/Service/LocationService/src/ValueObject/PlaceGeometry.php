<?php

namespace AsyncAws\LocationService\ValueObject;

/**
 * Places uses a point geometry to specify a location or a Place.
 */
final class PlaceGeometry
{
    /**
     * A single point geometry specifies a location for a Place using WGS 84 [^1] coordinates:
     *
     * - *x* — Specifies the x coordinate or longitude.
     * - *y* — Specifies the y coordinate or latitude.
     *
     * [^1]: https://gisgeography.com/wgs84-world-geodetic-system/
     *
     * @var float[]|null
     */
    private $point;

    /**
     * @param array{
     *   Point?: float[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->point = $input['Point'] ?? null;
    }

    /**
     * @param array{
     *   Point?: float[]|null,
     * }|PlaceGeometry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return float[]
     */
    public function getPoint(): array
    {
        return $this->point ?? [];
    }
}
