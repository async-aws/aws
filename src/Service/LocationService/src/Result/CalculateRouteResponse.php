<?php

namespace AsyncAws\LocationService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\LocationService\Enum\DistanceUnit;
use AsyncAws\LocationService\ValueObject\CalculateRouteSummary;
use AsyncAws\LocationService\ValueObject\Leg;
use AsyncAws\LocationService\ValueObject\LegGeometry;
use AsyncAws\LocationService\ValueObject\Step;

/**
 * Returns the result of the route calculation. Metadata includes legs and route summary.
 */
class CalculateRouteResponse extends Result
{
    /**
     * Contains details about each path between a pair of positions included along a route such as: `StartPosition`,
     * `EndPosition`, `Distance`, `DurationSeconds`, `Geometry`, and `Steps`. The number of legs returned corresponds to one
     * fewer than the total number of positions in the request.
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
     *
     * @var Leg[]
     */
    private $legs;

    /**
     * Contains information about the whole route, such as: `RouteBBox`, `DataSource`, `Distance`, `DistanceUnit`, and
     * `DurationSeconds`.
     *
     * @var CalculateRouteSummary
     */
    private $summary;

    /**
     * @return Leg[]
     */
    public function getLegs(): array
    {
        $this->initialize();

        return $this->legs;
    }

    public function getSummary(): CalculateRouteSummary
    {
        $this->initialize();

        return $this->summary;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->legs = $this->populateResultLegList($data['Legs'] ?? []);
        $this->summary = $this->populateResultCalculateRouteSummary($data['Summary']);
    }

    /**
     * @return float[]
     */
    private function populateResultBoundingBox(array $json): array
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

    private function populateResultCalculateRouteSummary(array $json): CalculateRouteSummary
    {
        return new CalculateRouteSummary([
            'RouteBBox' => $this->populateResultBoundingBox($json['RouteBBox']),
            'DataSource' => (string) $json['DataSource'],
            'Distance' => (float) $json['Distance'],
            'DurationSeconds' => (float) $json['DurationSeconds'],
            'DistanceUnit' => !DistanceUnit::exists((string) $json['DistanceUnit']) ? DistanceUnit::UNKNOWN_TO_SDK : (string) $json['DistanceUnit'],
        ]);
    }

    private function populateResultLeg(array $json): Leg
    {
        return new Leg([
            'StartPosition' => $this->populateResultPosition($json['StartPosition']),
            'EndPosition' => $this->populateResultPosition($json['EndPosition']),
            'Distance' => (float) $json['Distance'],
            'DurationSeconds' => (float) $json['DurationSeconds'],
            'Geometry' => empty($json['Geometry']) ? null : $this->populateResultLegGeometry($json['Geometry']),
            'Steps' => $this->populateResultStepList($json['Steps']),
        ]);
    }

    private function populateResultLegGeometry(array $json): LegGeometry
    {
        return new LegGeometry([
            'LineString' => !isset($json['LineString']) ? null : $this->populateResultLineString($json['LineString']),
        ]);
    }

    /**
     * @return Leg[]
     */
    private function populateResultLegList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultLeg($item);
        }

        return $items;
    }

    /**
     * @return float[][]
     */
    private function populateResultLineString(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultPosition($item);
        }

        return $items;
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

    private function populateResultStep(array $json): Step
    {
        return new Step([
            'StartPosition' => $this->populateResultPosition($json['StartPosition']),
            'EndPosition' => $this->populateResultPosition($json['EndPosition']),
            'Distance' => (float) $json['Distance'],
            'DurationSeconds' => (float) $json['DurationSeconds'],
            'GeometryOffset' => isset($json['GeometryOffset']) ? (int) $json['GeometryOffset'] : null,
        ]);
    }

    /**
     * @return Step[]
     */
    private function populateResultStepList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultStep($item);
        }

        return $items;
    }
}
