<?php

namespace AsyncAws\LocationService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\LocationService\Enum\DistanceUnit;
use AsyncAws\LocationService\Enum\OptimizationMode;
use AsyncAws\LocationService\Enum\TravelMode;
use AsyncAws\LocationService\ValueObject\CalculateRouteCarModeOptions;
use AsyncAws\LocationService\ValueObject\CalculateRouteTruckModeOptions;

final class CalculateRouteRequest extends Input
{
    /**
     * The name of the route calculator resource that you want to use to calculate the route.
     *
     * @required
     *
     * @var string|null
     */
    private $calculatorName;

    /**
     * The start position for the route. Defined in World Geodetic System (WGS 84) [^1] format: `[longitude, latitude]`.
     *
     * - For example, `[-123.115, 49.285]`
     *
     * > If you specify a departure that's not located on a road, Amazon Location moves the position to the nearest road
     * > [^2]. If Esri is the provider for your route calculator, specifying a route that is longer than 400 km returns a
     * > `400 RoutesValidationException` error.
     *
     * Valid Values: `[-180 to 180,-90 to 90]`
     *
     * [^1]: https://earth-info.nga.mil/index.php?dir=wgs84&amp;action=wgs84
     * [^2]: https://docs.aws.amazon.com/location/previous/developerguide/snap-to-nearby-road.html
     *
     * @required
     *
     * @var float[]|null
     */
    private $departurePosition;

    /**
     * The finish position for the route. Defined in World Geodetic System (WGS 84) [^1] format: `[longitude, latitude]`.
     *
     * - For example, `[-122.339, 47.615]`
     *
     * > If you specify a destination that's not located on a road, Amazon Location moves the position to the nearest road
     * > [^2].
     *
     * Valid Values: `[-180 to 180,-90 to 90]`
     *
     * [^1]: https://earth-info.nga.mil/index.php?dir=wgs84&amp;action=wgs84
     * [^2]: https://docs.aws.amazon.com/location/previous/developerguide/snap-to-nearby-road.html
     *
     * @required
     *
     * @var float[]|null
     */
    private $destinationPosition;

    /**
     * Specifies an ordered list of up to 23 intermediate positions to include along a route between the departure position
     * and destination position.
     *
     * - For example, from the `DeparturePosition``[-123.115, 49.285]`, the route follows the order that the waypoint
     *   positions are given `[[-122.757, 49.0021],[-122.349, 47.620]]`
     *
     * > If you specify a waypoint position that's not located on a road, Amazon Location moves the position to the nearest
     * > road [^1].
     * >
     * > Specifying more than 23 waypoints returns a `400 ValidationException` error.
     * >
     * > If Esri is the provider for your route calculator, specifying a route that is longer than 400 km returns a `400
     * > RoutesValidationException` error.
     *
     * Valid Values: `[-180 to 180,-90 to 90]`
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/snap-to-nearby-road.html
     *
     * @var float[][]|null
     */
    private $waypointPositions;

    /**
     * Specifies the mode of transport when calculating a route. Used in estimating the speed of travel and road
     * compatibility. You can choose `Car`, `Truck`, `Walking`, `Bicycle` or `Motorcycle` as options for the `TravelMode`.
     *
     * > `Bicycle` and `Motorcycle` are only valid when using Grab as a data provider, and only within Southeast Asia.
     * >
     * > `Truck` is not available for Grab.
     * >
     * > For more details on the using Grab for routing, including areas of coverage, see GrabMaps [^1] in the *Amazon
     * > Location Service Developer Guide*.
     *
     * The `TravelMode` you specify also determines how you specify route preferences:
     *
     * - If traveling by `Car` use the `CarModeOptions` parameter.
     * - If traveling by `Truck` use the `TruckModeOptions` parameter.
     *
     * Default Value: `Car`
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/grab.html
     *
     * @var TravelMode::*|null
     */
    private $travelMode;

    /**
     * Specifies the desired time of departure. Uses the given time to calculate the route. Otherwise, the best time of day
     * to travel with the best traffic conditions is used to calculate the route.
     *
     * - In ISO 8601 [^1] format: `YYYY-MM-DDThh:mm:ss.sssZ`. For example, `2020–07-2T12:15:20.000Z+01:00`
     *
     * [^1]: https://www.iso.org/iso-8601-date-and-time-format.html
     *
     * @var \DateTimeImmutable|null
     */
    private $departureTime;

    /**
     * Sets the time of departure as the current time. Uses the current time to calculate a route. Otherwise, the best time
     * of day to travel with the best traffic conditions is used to calculate the route.
     *
     * Default Value: `false`
     *
     * Valid Values: `false` | `true`
     *
     * @var bool|null
     */
    private $departNow;

    /**
     * Set the unit system to specify the distance.
     *
     * Default Value: `Kilometers`
     *
     * @var DistanceUnit::*|null
     */
    private $distanceUnit;

    /**
     * Set to include the geometry details in the result for each path between a pair of positions.
     *
     * Default Value: `false`
     *
     * Valid Values: `false` | `true`
     *
     * @var bool|null
     */
    private $includeLegGeometry;

    /**
     * Specifies route preferences when traveling by `Car`, such as avoiding routes that use ferries or tolls.
     *
     * Requirements: `TravelMode` must be specified as `Car`.
     *
     * @var CalculateRouteCarModeOptions|null
     */
    private $carModeOptions;

    /**
     * Specifies route preferences when traveling by `Truck`, such as avoiding routes that use ferries or tolls, and truck
     * specifications to consider when choosing an optimal road.
     *
     * Requirements: `TravelMode` must be specified as `Truck`.
     *
     * @var CalculateRouteTruckModeOptions|null
     */
    private $truckModeOptions;

    /**
     * Specifies the desired time of arrival. Uses the given time to calculate the route. Otherwise, the best time of day to
     * travel with the best traffic conditions is used to calculate the route.
     *
     * > ArrivalTime is not supported Esri.
     *
     * @var \DateTimeImmutable|null
     */
    private $arrivalTime;

    /**
     * Specifies the distance to optimize for when calculating a route.
     *
     * @var OptimizationMode::*|null
     */
    private $optimizeFor;

    /**
     * The optional API key [^1] to authorize the request.
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/using-apikeys.html
     *
     * @var string|null
     */
    private $key;

    /**
     * @param array{
     *   CalculatorName?: string,
     *   DeparturePosition?: float[],
     *   DestinationPosition?: float[],
     *   WaypointPositions?: array[]|null,
     *   TravelMode?: TravelMode::*|null,
     *   DepartureTime?: \DateTimeImmutable|string|null,
     *   DepartNow?: bool|null,
     *   DistanceUnit?: DistanceUnit::*|null,
     *   IncludeLegGeometry?: bool|null,
     *   CarModeOptions?: CalculateRouteCarModeOptions|array|null,
     *   TruckModeOptions?: CalculateRouteTruckModeOptions|array|null,
     *   ArrivalTime?: \DateTimeImmutable|string|null,
     *   OptimizeFor?: OptimizationMode::*|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->calculatorName = $input['CalculatorName'] ?? null;
        $this->departurePosition = $input['DeparturePosition'] ?? null;
        $this->destinationPosition = $input['DestinationPosition'] ?? null;
        $this->waypointPositions = $input['WaypointPositions'] ?? null;
        $this->travelMode = $input['TravelMode'] ?? null;
        $this->departureTime = !isset($input['DepartureTime']) ? null : ($input['DepartureTime'] instanceof \DateTimeImmutable ? $input['DepartureTime'] : new \DateTimeImmutable($input['DepartureTime']));
        $this->departNow = $input['DepartNow'] ?? null;
        $this->distanceUnit = $input['DistanceUnit'] ?? null;
        $this->includeLegGeometry = $input['IncludeLegGeometry'] ?? null;
        $this->carModeOptions = isset($input['CarModeOptions']) ? CalculateRouteCarModeOptions::create($input['CarModeOptions']) : null;
        $this->truckModeOptions = isset($input['TruckModeOptions']) ? CalculateRouteTruckModeOptions::create($input['TruckModeOptions']) : null;
        $this->arrivalTime = !isset($input['ArrivalTime']) ? null : ($input['ArrivalTime'] instanceof \DateTimeImmutable ? $input['ArrivalTime'] : new \DateTimeImmutable($input['ArrivalTime']));
        $this->optimizeFor = $input['OptimizeFor'] ?? null;
        $this->key = $input['Key'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   CalculatorName?: string,
     *   DeparturePosition?: float[],
     *   DestinationPosition?: float[],
     *   WaypointPositions?: array[]|null,
     *   TravelMode?: TravelMode::*|null,
     *   DepartureTime?: \DateTimeImmutable|string|null,
     *   DepartNow?: bool|null,
     *   DistanceUnit?: DistanceUnit::*|null,
     *   IncludeLegGeometry?: bool|null,
     *   CarModeOptions?: CalculateRouteCarModeOptions|array|null,
     *   TruckModeOptions?: CalculateRouteTruckModeOptions|array|null,
     *   ArrivalTime?: \DateTimeImmutable|string|null,
     *   OptimizeFor?: OptimizationMode::*|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * }|CalculateRouteRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArrivalTime(): ?\DateTimeImmutable
    {
        return $this->arrivalTime;
    }

    public function getCalculatorName(): ?string
    {
        return $this->calculatorName;
    }

    public function getCarModeOptions(): ?CalculateRouteCarModeOptions
    {
        return $this->carModeOptions;
    }

    public function getDepartNow(): ?bool
    {
        return $this->departNow;
    }

    /**
     * @return float[]
     */
    public function getDeparturePosition(): array
    {
        return $this->departurePosition ?? [];
    }

    public function getDepartureTime(): ?\DateTimeImmutable
    {
        return $this->departureTime;
    }

    /**
     * @return float[]
     */
    public function getDestinationPosition(): array
    {
        return $this->destinationPosition ?? [];
    }

    /**
     * @return DistanceUnit::*|null
     */
    public function getDistanceUnit(): ?string
    {
        return $this->distanceUnit;
    }

    public function getIncludeLegGeometry(): ?bool
    {
        return $this->includeLegGeometry;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return OptimizationMode::*|null
     */
    public function getOptimizeFor(): ?string
    {
        return $this->optimizeFor;
    }

    /**
     * @return TravelMode::*|null
     */
    public function getTravelMode(): ?string
    {
        return $this->travelMode;
    }

    public function getTruckModeOptions(): ?CalculateRouteTruckModeOptions
    {
        return $this->truckModeOptions;
    }

    /**
     * @return float[][]
     */
    public function getWaypointPositions(): array
    {
        return $this->waypointPositions ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];
        if (null !== $this->key) {
            $query['key'] = $this->key;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->calculatorName) {
            throw new InvalidArgument(\sprintf('Missing parameter "CalculatorName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['CalculatorName'] = $v;
        $uriString = '/routes/v0/calculators/' . rawurlencode($uri['CalculatorName']) . '/calculate/route';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body), 'routes.');
    }

    public function setArrivalTime(?\DateTimeImmutable $value): self
    {
        $this->arrivalTime = $value;

        return $this;
    }

    public function setCalculatorName(?string $value): self
    {
        $this->calculatorName = $value;

        return $this;
    }

    public function setCarModeOptions(?CalculateRouteCarModeOptions $value): self
    {
        $this->carModeOptions = $value;

        return $this;
    }

    public function setDepartNow(?bool $value): self
    {
        $this->departNow = $value;

        return $this;
    }

    /**
     * @param float[] $value
     */
    public function setDeparturePosition(array $value): self
    {
        $this->departurePosition = $value;

        return $this;
    }

    public function setDepartureTime(?\DateTimeImmutable $value): self
    {
        $this->departureTime = $value;

        return $this;
    }

    /**
     * @param float[] $value
     */
    public function setDestinationPosition(array $value): self
    {
        $this->destinationPosition = $value;

        return $this;
    }

    /**
     * @param DistanceUnit::*|null $value
     */
    public function setDistanceUnit(?string $value): self
    {
        $this->distanceUnit = $value;

        return $this;
    }

    public function setIncludeLegGeometry(?bool $value): self
    {
        $this->includeLegGeometry = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->key = $value;

        return $this;
    }

    /**
     * @param OptimizationMode::*|null $value
     */
    public function setOptimizeFor(?string $value): self
    {
        $this->optimizeFor = $value;

        return $this;
    }

    /**
     * @param TravelMode::*|null $value
     */
    public function setTravelMode(?string $value): self
    {
        $this->travelMode = $value;

        return $this;
    }

    public function setTruckModeOptions(?CalculateRouteTruckModeOptions $value): self
    {
        $this->truckModeOptions = $value;

        return $this;
    }

    /**
     * @param float[][] $value
     */
    public function setWaypointPositions(array $value): self
    {
        $this->waypointPositions = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->departurePosition) {
            throw new InvalidArgument(\sprintf('Missing parameter "DeparturePosition" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['DeparturePosition'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['DeparturePosition'][$index] = $listValue;
        }

        if (null === $v = $this->destinationPosition) {
            throw new InvalidArgument(\sprintf('Missing parameter "DestinationPosition" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['DestinationPosition'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['DestinationPosition'][$index] = $listValue;
        }

        if (null !== $v = $this->waypointPositions) {
            $index = -1;
            $payload['WaypointPositions'] = [];
            foreach ($v as $listValue) {
                ++$index;

                $index1 = -1;
                $payload['WaypointPositions'][$index] = [];
                foreach ($listValue as $listValue1) {
                    ++$index1;
                    $payload['WaypointPositions'][$index][$index1] = $listValue1;
                }
            }
        }
        if (null !== $v = $this->travelMode) {
            if (!TravelMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "TravelMode" for "%s". The value "%s" is not a valid "TravelMode".', __CLASS__, $v));
            }
            $payload['TravelMode'] = $v;
        }
        if (null !== $v = $this->departureTime) {
            $payload['DepartureTime'] = $v->format(\DateTimeInterface::ATOM);
        }
        if (null !== $v = $this->departNow) {
            $payload['DepartNow'] = (bool) $v;
        }
        if (null !== $v = $this->distanceUnit) {
            if (!DistanceUnit::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "DistanceUnit" for "%s". The value "%s" is not a valid "DistanceUnit".', __CLASS__, $v));
            }
            $payload['DistanceUnit'] = $v;
        }
        if (null !== $v = $this->includeLegGeometry) {
            $payload['IncludeLegGeometry'] = (bool) $v;
        }
        if (null !== $v = $this->carModeOptions) {
            $payload['CarModeOptions'] = $v->requestBody();
        }
        if (null !== $v = $this->truckModeOptions) {
            $payload['TruckModeOptions'] = $v->requestBody();
        }
        if (null !== $v = $this->arrivalTime) {
            $payload['ArrivalTime'] = $v->format(\DateTimeInterface::ATOM);
        }
        if (null !== $v = $this->optimizeFor) {
            if (!OptimizationMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "OptimizeFor" for "%s". The value "%s" is not a valid "OptimizationMode".', __CLASS__, $v));
            }
            $payload['OptimizeFor'] = $v;
        }

        return $payload;
    }
}
