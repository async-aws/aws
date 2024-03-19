<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\LocationService\Enum\DistanceUnit;

/**
 * A summary of the calculated route matrix.
 */
final class CalculateRouteMatrixSummary
{
    /**
     * The data provider of traffic and road network data used to calculate the routes. Indicates one of the available
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
     * The unit of measurement for route distances.
     *
     * @var DistanceUnit::*
     */
    private $distanceUnit;

    /**
     * The count of error results in the route matrix. If this number is 0, all routes were calculated successfully.
     *
     * @var int
     */
    private $errorCount;

    /**
     * The count of cells in the route matrix. Equal to the number of `DeparturePositions` multiplied by the number of
     * `DestinationPositions`.
     *
     * @var int
     */
    private $routeCount;

    /**
     * @param array{
     *   DataSource: string,
     *   DistanceUnit: DistanceUnit::*,
     *   ErrorCount: int,
     *   RouteCount: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dataSource = $input['DataSource'] ?? $this->throwException(new InvalidArgument('Missing required field "DataSource".'));
        $this->distanceUnit = $input['DistanceUnit'] ?? $this->throwException(new InvalidArgument('Missing required field "DistanceUnit".'));
        $this->errorCount = $input['ErrorCount'] ?? $this->throwException(new InvalidArgument('Missing required field "ErrorCount".'));
        $this->routeCount = $input['RouteCount'] ?? $this->throwException(new InvalidArgument('Missing required field "RouteCount".'));
    }

    /**
     * @param array{
     *   DataSource: string,
     *   DistanceUnit: DistanceUnit::*,
     *   ErrorCount: int,
     *   RouteCount: int,
     * }|CalculateRouteMatrixSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataSource(): string
    {
        return $this->dataSource;
    }

    /**
     * @return DistanceUnit::*
     */
    public function getDistanceUnit(): string
    {
        return $this->distanceUnit;
    }

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    public function getRouteCount(): int
    {
        return $this->routeCount;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
