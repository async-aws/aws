<?php

namespace AsyncAws\LocationService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\LocationService\ValueObject\CalculateRouteMatrixSummary;
use AsyncAws\LocationService\ValueObject\RouteMatrixEntry;
use AsyncAws\LocationService\ValueObject\RouteMatrixEntryError;

/**
 * Returns the result of the route matrix calculation.
 */
class CalculateRouteMatrixResponse extends Result
{
    /**
     * The calculated route matrix containing the results for all pairs of `DeparturePositions` to `DestinationPositions`.
     * Each row corresponds to one entry in `DeparturePositions`. Each entry in the row corresponds to the route from that
     * entry in `DeparturePositions` to an entry in `DestinationPositions`.
     *
     * @var RouteMatrixEntry[][]
     */
    private $routeMatrix;

    /**
     * For routes calculated using an Esri route calculator resource, departure positions are snapped to the closest road.
     * For Esri route calculator resources, this returns the list of departure/origin positions used for calculation of the
     * `RouteMatrix`.
     *
     * @var float[][]
     */
    private $snappedDeparturePositions;

    /**
     * The list of destination positions for the route matrix used for calculation of the `RouteMatrix`.
     *
     * @var float[][]
     */
    private $snappedDestinationPositions;

    /**
     * Contains information about the route matrix, `DataSource`, `DistanceUnit`, `RouteCount` and `ErrorCount`.
     *
     * @var CalculateRouteMatrixSummary
     */
    private $summary;

    /**
     * @return RouteMatrixEntry[][]
     */
    public function getRouteMatrix(): array
    {
        $this->initialize();

        return $this->routeMatrix;
    }

    /**
     * @return float[][]
     */
    public function getSnappedDeparturePositions(): array
    {
        $this->initialize();

        return $this->snappedDeparturePositions;
    }

    /**
     * @return float[][]
     */
    public function getSnappedDestinationPositions(): array
    {
        $this->initialize();

        return $this->snappedDestinationPositions;
    }

    public function getSummary(): CalculateRouteMatrixSummary
    {
        $this->initialize();

        return $this->summary;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->routeMatrix = $this->populateResultRouteMatrix($data['RouteMatrix'] ?? []);
        $this->snappedDeparturePositions = empty($data['SnappedDeparturePositions']) ? [] : $this->populateResultCalculateRouteMatrixResponseSnappedDeparturePositionsList($data['SnappedDeparturePositions']);
        $this->snappedDestinationPositions = empty($data['SnappedDestinationPositions']) ? [] : $this->populateResultCalculateRouteMatrixResponseSnappedDestinationPositionsList($data['SnappedDestinationPositions']);
        $this->summary = $this->populateResultCalculateRouteMatrixSummary($data['Summary']);
    }

    /**
     * @return float[][]
     */
    private function populateResultCalculateRouteMatrixResponseSnappedDeparturePositionsList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultPosition($item ?? []);
        }

        return $items;
    }

    /**
     * @return float[][]
     */
    private function populateResultCalculateRouteMatrixResponseSnappedDestinationPositionsList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultPosition($item ?? []);
        }

        return $items;
    }

    private function populateResultCalculateRouteMatrixSummary(array $json): CalculateRouteMatrixSummary
    {
        return new CalculateRouteMatrixSummary([
            'DataSource' => (string) $json['DataSource'],
            'RouteCount' => (int) $json['RouteCount'],
            'ErrorCount' => (int) $json['ErrorCount'],
            'DistanceUnit' => (string) $json['DistanceUnit'],
        ]);
    }

    /**
     * @return float[]
     */
    private function populateResultPosition(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (float) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return RouteMatrixEntry[][]
     */
    private function populateResultRouteMatrix(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultRouteMatrixRow($item ?? []);
        }

        return $items;
    }

    private function populateResultRouteMatrixEntry(array $json): RouteMatrixEntry
    {
        return new RouteMatrixEntry([
            'Distance' => isset($json['Distance']) ? (float) $json['Distance'] : null,
            'DurationSeconds' => isset($json['DurationSeconds']) ? (float) $json['DurationSeconds'] : null,
            'Error' => empty($json['Error']) ? null : $this->populateResultRouteMatrixEntryError($json['Error']),
        ]);
    }

    private function populateResultRouteMatrixEntryError(array $json): RouteMatrixEntryError
    {
        return new RouteMatrixEntryError([
            'Code' => (string) $json['Code'],
            'Message' => isset($json['Message']) ? (string) $json['Message'] : null,
        ]);
    }

    /**
     * @return RouteMatrixEntry[]
     */
    private function populateResultRouteMatrixRow(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultRouteMatrixEntry($item);
        }

        return $items;
    }
}
