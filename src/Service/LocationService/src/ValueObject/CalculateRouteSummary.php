<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\LocationService\Enum\DistanceUnit;

/**
 * A summary of the calculated route.
 */
final class CalculateRouteSummary
{
    /**
     * Specifies a geographical box surrounding a route. Used to zoom into a route when displaying it in a map. For example,
     * `[min x, min y, max x, max y]`.
     *
     * The first 2 `bbox` parameters describe the lower southwest corner:
     *
     * - The first `bbox` position is the X coordinate or longitude of the lower southwest corner.
     * - The second `bbox` position is the Y coordinate or latitude of the lower southwest corner.
     *
     * The next 2 `bbox` parameters describe the upper northeast corner:
     *
     * - The third `bbox` position is the X coordinate, or longitude of the upper northeast corner.
     * - The fourth `bbox` position is the Y coordinate, or latitude of the upper northeast corner.
     *
     * @var float[]
     */
    private $routeBbox;

    /**
     * The data provider of traffic and road network data used to calculate the route. Indicates one of the available
     * providers:
     *
     * - `Esri`
     * - `Grab`
     * - `Here`
     *
     * For more information about data providers, see Amazon Location Service data providers [^1].
     *
     * [^1]: https://docs.aws.amazon.com/location/latest/developerguide/what-is-data-provider.html
     *
     * @var string
     */
    private $dataSource;

    /**
     * The total distance covered by the route. The sum of the distance travelled between every stop on the route.
     *
     * > If Esri is the data source for the route calculator, the route distance can’t be greater than 400 km. If the
     * > route exceeds 400 km, the response is a `400 RoutesValidationException` error.
     *
     * @var float
     */
    private $distance;

    /**
     * The total travel time for the route measured in seconds. The sum of the travel time between every stop on the route.
     *
     * @var float
     */
    private $durationSeconds;

    /**
     * The unit of measurement for route distances.
     *
     * @var DistanceUnit::*|string
     */
    private $distanceUnit;

    /**
     * @param array{
     *   RouteBBox: float[],
     *   DataSource: string,
     *   Distance: float,
     *   DurationSeconds: float,
     *   DistanceUnit: DistanceUnit::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->routeBbox = $input['RouteBBox'] ?? $this->throwException(new InvalidArgument('Missing required field "RouteBBox".'));
        $this->dataSource = $input['DataSource'] ?? $this->throwException(new InvalidArgument('Missing required field "DataSource".'));
        $this->distance = $input['Distance'] ?? $this->throwException(new InvalidArgument('Missing required field "Distance".'));
        $this->durationSeconds = $input['DurationSeconds'] ?? $this->throwException(new InvalidArgument('Missing required field "DurationSeconds".'));
        $this->distanceUnit = $input['DistanceUnit'] ?? $this->throwException(new InvalidArgument('Missing required field "DistanceUnit".'));
    }

    /**
     * @param array{
     *   RouteBBox: float[],
     *   DataSource: string,
     *   Distance: float,
     *   DurationSeconds: float,
     *   DistanceUnit: DistanceUnit::*|string,
     * }|CalculateRouteSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataSource(): string
    {
        return $this->dataSource;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    /**
     * @return DistanceUnit::*|string
     */
    public function getDistanceUnit(): string
    {
        return $this->distanceUnit;
    }

    public function getDurationSeconds(): float
    {
        return $this->durationSeconds;
    }

    /**
     * @return float[]
     */
    public function getRouteBbox(): array
    {
        return $this->routeBbox;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
