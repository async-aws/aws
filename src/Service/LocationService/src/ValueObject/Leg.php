<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains the calculated route's details for each path between a pair of positions. The number of legs returned
 * corresponds to one fewer than the total number of positions in the request.
 *
 * For example, a route with a departure position and destination position returns one leg with the positions snapped to
 * a nearby road [^1]:
 *
 * - The `StartPosition` is the departure position.
 * - The `EndPosition` is the destination position.
 *
 * A route with a waypoint between the departure and destination position returns two legs with the positions snapped to
 * a nearby road:
 *
 * - Leg 1: The `StartPosition` is the departure position . The `EndPosition` is the waypoint positon.
 * - Leg 2: The `StartPosition` is the waypoint position. The `EndPosition` is the destination position.
 *
 * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/snap-to-nearby-road.html
 */
final class Leg
{
    /**
     * The starting position of the leg. Follows the format `[longitude,latitude]`.
     *
     * > If the `StartPosition` isn't located on a road, it's snapped to a nearby road [^1].
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/snap-to-nearby-road.html
     *
     * @var float[]
     */
    private $startPosition;

    /**
     * The terminating position of the leg. Follows the format `[longitude,latitude]`.
     *
     * > If the `EndPosition` isn't located on a road, it's snapped to a nearby road [^1].
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/nap-to-nearby-road.html
     *
     * @var float[]
     */
    private $endPosition;

    /**
     * The distance between the leg's `StartPosition` and `EndPosition` along a calculated route.
     *
     * - The default measurement is `Kilometers` unless the request specifies a `DistanceUnit` of `Miles`.
     *
     * @var float
     */
    private $distance;

    /**
     * The estimated travel time between the leg's `StartPosition` and `EndPosition`. The travel mode and departure time
     * that you specify in the request determines the calculated time.
     *
     * @var float
     */
    private $durationSeconds;

    /**
     * Contains the calculated route's path as a linestring geometry.
     *
     * @var LegGeometry|null
     */
    private $geometry;

    /**
     * Contains a list of steps, which represent subsections of a leg. Each step provides instructions for how to move to
     * the next step in the leg such as the step's start position, end position, travel distance, travel duration, and
     * geometry offset.
     *
     * @var Step[]
     */
    private $steps;

    /**
     * @param array{
     *   StartPosition: float[],
     *   EndPosition: float[],
     *   Distance: float,
     *   DurationSeconds: float,
     *   Geometry?: null|LegGeometry|array,
     *   Steps: array<Step|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->startPosition = $input['StartPosition'] ?? $this->throwException(new InvalidArgument('Missing required field "StartPosition".'));
        $this->endPosition = $input['EndPosition'] ?? $this->throwException(new InvalidArgument('Missing required field "EndPosition".'));
        $this->distance = $input['Distance'] ?? $this->throwException(new InvalidArgument('Missing required field "Distance".'));
        $this->durationSeconds = $input['DurationSeconds'] ?? $this->throwException(new InvalidArgument('Missing required field "DurationSeconds".'));
        $this->geometry = isset($input['Geometry']) ? LegGeometry::create($input['Geometry']) : null;
        $this->steps = isset($input['Steps']) ? array_map([Step::class, 'create'], $input['Steps']) : $this->throwException(new InvalidArgument('Missing required field "Steps".'));
    }

    /**
     * @param array{
     *   StartPosition: float[],
     *   EndPosition: float[],
     *   Distance: float,
     *   DurationSeconds: float,
     *   Geometry?: null|LegGeometry|array,
     *   Steps: array<Step|array>,
     * }|Leg $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function getDurationSeconds(): float
    {
        return $this->durationSeconds;
    }

    /**
     * @return float[]
     */
    public function getEndPosition(): array
    {
        return $this->endPosition;
    }

    public function getGeometry(): ?LegGeometry
    {
        return $this->geometry;
    }

    /**
     * @return float[]
     */
    public function getStartPosition(): array
    {
        return $this->startPosition;
    }

    /**
     * @return Step[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
