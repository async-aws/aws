<?php

namespace AsyncAws\LocationService\ValueObject;

/**
 * The result for the calculated route of one `DeparturePosition``DestinationPosition` pair.
 */
final class RouteMatrixEntry
{
    /**
     * The total distance of travel for the route.
     *
     * @var float|null
     */
    private $distance;

    /**
     * The expected duration of travel for the route.
     *
     * @var float|null
     */
    private $durationSeconds;

    /**
     * An error corresponding to the calculation of a route between the `DeparturePosition` and `DestinationPosition`.
     *
     * @var RouteMatrixEntryError|null
     */
    private $error;

    /**
     * @param array{
     *   Distance?: null|float,
     *   DurationSeconds?: null|float,
     *   Error?: null|RouteMatrixEntryError|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->distance = $input['Distance'] ?? null;
        $this->durationSeconds = $input['DurationSeconds'] ?? null;
        $this->error = isset($input['Error']) ? RouteMatrixEntryError::create($input['Error']) : null;
    }

    /**
     * @param array{
     *   Distance?: null|float,
     *   DurationSeconds?: null|float,
     *   Error?: null|RouteMatrixEntryError|array,
     * }|RouteMatrixEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function getDurationSeconds(): ?float
    {
        return $this->durationSeconds;
    }

    public function getError(): ?RouteMatrixEntryError
    {
        return $this->error;
    }
}
