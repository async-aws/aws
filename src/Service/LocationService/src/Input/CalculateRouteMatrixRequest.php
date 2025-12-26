<?php

namespace AsyncAws\LocationService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\LocationService\Enum\DistanceUnit;
use AsyncAws\LocationService\Enum\TravelMode;
use AsyncAws\LocationService\ValueObject\CalculateRouteCarModeOptions;
use AsyncAws\LocationService\ValueObject\CalculateRouteTruckModeOptions;

final class CalculateRouteMatrixRequest extends Input
{
    /**
     * The name of the route calculator resource that you want to use to calculate the route matrix.
     *
     * @required
     *
     * @var string|null
     */
    private $calculatorName;

    /**
     * The list of departure (origin) positions for the route matrix. An array of points, each of which is itself a 2-value
     * array defined in WGS 84 [^1] format: `[longitude, latitude]`. For example, `[-123.115, 49.285]`.
     *
     * ! Depending on the data provider selected in the route calculator resource there may be additional restrictions on
     * ! the inputs you can choose. See Position restrictions [^2] in the *Amazon Location Service Developer Guide*.
     *
     * > For route calculators that use Esri as the data provider, if you specify a departure that's not located on a road,
     * > Amazon Location moves the position to the nearest road [^3]. The snapped value is available in the result in
     * > `SnappedDeparturePositions`.
     *
     * Valid Values: `[-180 to 180,-90 to 90]`
     *
     * [^1]: https://earth-info.nga.mil/GandG/wgs84/index.html
     * [^2]: https://docs.aws.amazon.com/location/previous/developerguide/calculate-route-matrix.html#matrix-routing-position-limits
     * [^3]: https://docs.aws.amazon.com/location/previous/developerguide/snap-to-nearby-road.html
     *
     * @required
     *
     * @var float[][]|null
     */
    private $departurePositions;

    /**
     * The list of destination positions for the route matrix. An array of points, each of which is itself a 2-value array
     * defined in WGS 84 [^1] format: `[longitude, latitude]`. For example, `[-122.339, 47.615]`.
     *
     * ! Depending on the data provider selected in the route calculator resource there may be additional restrictions on
     * ! the inputs you can choose. See Position restrictions [^2] in the *Amazon Location Service Developer Guide*.
     *
     * > For route calculators that use Esri as the data provider, if you specify a destination that's not located on a
     * > road, Amazon Location moves the position to the nearest road [^3]. The snapped value is available in the result in
     * > `SnappedDestinationPositions`.
     *
     * Valid Values: `[-180 to 180,-90 to 90]`
     *
     * [^1]: https://earth-info.nga.mil/GandG/wgs84/index.html
     * [^2]: https://docs.aws.amazon.com/location/previous/developerguide/calculate-route-matrix.html#matrix-routing-position-limits
     * [^3]: https://docs.aws.amazon.com/location/previous/developerguide/snap-to-nearby-road.html
     *
     * @required
     *
     * @var float[][]|null
     */
    private $destinationPositions;

    /**
     * Specifies the mode of transport when calculating a route. Used in estimating the speed of travel and road
     * compatibility.
     *
     * The `TravelMode` you specify also determines how you specify route preferences:
     *
     * - If traveling by `Car` use the `CarModeOptions` parameter.
     * - If traveling by `Truck` use the `TruckModeOptions` parameter.
     *
     * > `Bicycle` or `Motorcycle` are only valid when using `Grab` as a data provider, and only within Southeast Asia.
     * >
     * > `Truck` is not available for Grab.
     * >
     * > For more information about using Grab as a data provider, see GrabMaps [^1] in the *Amazon Location Service
     * > Developer Guide*.
     *
     * Default Value: `Car`
     *
     * [^1]: https://docs.aws.amazon.com/location/previous/developerguide/grab.html
     *
     * @var TravelMode::*|null
     */
    private $travelMode;

    /**
     * Specifies the desired time of departure. Uses the given time to calculate the route matrix. You can't set both
     * `DepartureTime` and `DepartNow`. If neither is set, the best time of day to travel with the best traffic conditions
     * is used to calculate the route matrix.
     *
     * > Setting a departure time in the past returns a `400 ValidationException` error.
     *
     * - In ISO 8601 [^1] format: `YYYY-MM-DDThh:mm:ss.sssZ`. For example, `2020–07-2T12:15:20.000Z+01:00`
     *
     * [^1]: https://www.iso.org/iso-8601-date-and-time-format.html
     *
     * @var \DateTimeImmutable|null
     */
    private $departureTime;

    /**
     * Sets the time of departure as the current time. Uses the current time to calculate the route matrix. You can't set
     * both `DepartureTime` and `DepartNow`. If neither is set, the best time of day to travel with the best traffic
     * conditions is used to calculate the route matrix.
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
     *   DeparturePositions?: array[],
     *   DestinationPositions?: array[],
     *   TravelMode?: TravelMode::*|null,
     *   DepartureTime?: \DateTimeImmutable|string|null,
     *   DepartNow?: bool|null,
     *   DistanceUnit?: DistanceUnit::*|null,
     *   CarModeOptions?: CalculateRouteCarModeOptions|array|null,
     *   TruckModeOptions?: CalculateRouteTruckModeOptions|array|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->calculatorName = $input['CalculatorName'] ?? null;
        $this->departurePositions = $input['DeparturePositions'] ?? null;
        $this->destinationPositions = $input['DestinationPositions'] ?? null;
        $this->travelMode = $input['TravelMode'] ?? null;
        $this->departureTime = !isset($input['DepartureTime']) ? null : ($input['DepartureTime'] instanceof \DateTimeImmutable ? $input['DepartureTime'] : new \DateTimeImmutable($input['DepartureTime']));
        $this->departNow = $input['DepartNow'] ?? null;
        $this->distanceUnit = $input['DistanceUnit'] ?? null;
        $this->carModeOptions = isset($input['CarModeOptions']) ? CalculateRouteCarModeOptions::create($input['CarModeOptions']) : null;
        $this->truckModeOptions = isset($input['TruckModeOptions']) ? CalculateRouteTruckModeOptions::create($input['TruckModeOptions']) : null;
        $this->key = $input['Key'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   CalculatorName?: string,
     *   DeparturePositions?: array[],
     *   DestinationPositions?: array[],
     *   TravelMode?: TravelMode::*|null,
     *   DepartureTime?: \DateTimeImmutable|string|null,
     *   DepartNow?: bool|null,
     *   DistanceUnit?: DistanceUnit::*|null,
     *   CarModeOptions?: CalculateRouteCarModeOptions|array|null,
     *   TruckModeOptions?: CalculateRouteTruckModeOptions|array|null,
     *   Key?: string|null,
     *   '@region'?: string|null,
     * }|CalculateRouteMatrixRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
     * @return float[][]
     */
    public function getDeparturePositions(): array
    {
        return $this->departurePositions ?? [];
    }

    public function getDepartureTime(): ?\DateTimeImmutable
    {
        return $this->departureTime;
    }

    /**
     * @return float[][]
     */
    public function getDestinationPositions(): array
    {
        return $this->destinationPositions ?? [];
    }

    /**
     * @return DistanceUnit::*|null
     */
    public function getDistanceUnit(): ?string
    {
        return $this->distanceUnit;
    }

    public function getKey(): ?string
    {
        return $this->key;
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
        $uriString = '/routes/v0/calculators/' . rawurlencode($uri['CalculatorName']) . '/calculate/route-matrix';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body), 'routes.');
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
     * @param float[][] $value
     */
    public function setDeparturePositions(array $value): self
    {
        $this->departurePositions = $value;

        return $this;
    }

    public function setDepartureTime(?\DateTimeImmutable $value): self
    {
        $this->departureTime = $value;

        return $this;
    }

    /**
     * @param float[][] $value
     */
    public function setDestinationPositions(array $value): self
    {
        $this->destinationPositions = $value;

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

    public function setKey(?string $value): self
    {
        $this->key = $value;

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

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->departurePositions) {
            throw new InvalidArgument(\sprintf('Missing parameter "DeparturePositions" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['DeparturePositions'] = [];
        foreach ($v as $listValue) {
            ++$index;

            $index1 = -1;
            $payload['DeparturePositions'][$index] = [];
            foreach ($listValue as $listValue1) {
                ++$index1;
                $payload['DeparturePositions'][$index][$index1] = $listValue1;
            }
        }

        if (null === $v = $this->destinationPositions) {
            throw new InvalidArgument(\sprintf('Missing parameter "DestinationPositions" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['DestinationPositions'] = [];
        foreach ($v as $listValue) {
            ++$index;

            $index1 = -1;
            $payload['DestinationPositions'][$index] = [];
            foreach ($listValue as $listValue1) {
                ++$index1;
                $payload['DestinationPositions'][$index][$index1] = $listValue1;
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
        if (null !== $v = $this->carModeOptions) {
            $payload['CarModeOptions'] = $v->requestBody();
        }
        if (null !== $v = $this->truckModeOptions) {
            $payload['TruckModeOptions'] = $v->requestBody();
        }

        return $payload;
    }
}
