<?php

namespace AsyncAws\LocationService\ValueObject;

/**
 * Contains the geometry details for each path between a pair of positions. Used in plotting a route leg on a map.
 */
final class LegGeometry
{
    /**
     * An ordered list of positions used to plot a route on a map.
     *
     * The first position is closest to the start position for the leg, and the last position is the closest to the end
     * position for the leg.
     *
     * - For example, `[[-123.117, 49.284],[-123.115, 49.285],[-123.115, 49.285]]`
     *
     * @var float[][]|null
     */
    private $lineString;

    /**
     * @param array{
     *   LineString?: array[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->lineString = $input['LineString'] ?? null;
    }

    /**
     * @param array{
     *   LineString?: array[]|null,
     * }|LegGeometry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return float[][]
     */
    public function getLineString(): array
    {
        return $this->lineString ?? [];
    }
}
