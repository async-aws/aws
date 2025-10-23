<?php

namespace AsyncAws\LocationService\ValueObject;

/**
 * Contains details about additional route preferences for requests that specify `TravelMode` as `Truck`.
 */
final class CalculateRouteTruckModeOptions
{
    /**
     * Avoids ferries when calculating routes.
     *
     * Default Value: `false`
     *
     * Valid Values: `false` | `true`
     *
     * @var bool|null
     */
    private $avoidFerries;

    /**
     * Avoids tolls when calculating routes.
     *
     * Default Value: `false`
     *
     * Valid Values: `false` | `true`
     *
     * @var bool|null
     */
    private $avoidTolls;

    /**
     * Specifies the truck's dimension specifications including length, height, width, and unit of measurement. Used to
     * avoid roads that can't support the truck's dimensions.
     *
     * @var TruckDimensions|null
     */
    private $dimensions;

    /**
     * Specifies the truck's weight specifications including total weight and unit of measurement. Used to avoid roads that
     * can't support the truck's weight.
     *
     * @var TruckWeight|null
     */
    private $weight;

    /**
     * @param array{
     *   AvoidFerries?: bool|null,
     *   AvoidTolls?: bool|null,
     *   Dimensions?: TruckDimensions|array|null,
     *   Weight?: TruckWeight|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->avoidFerries = $input['AvoidFerries'] ?? null;
        $this->avoidTolls = $input['AvoidTolls'] ?? null;
        $this->dimensions = isset($input['Dimensions']) ? TruckDimensions::create($input['Dimensions']) : null;
        $this->weight = isset($input['Weight']) ? TruckWeight::create($input['Weight']) : null;
    }

    /**
     * @param array{
     *   AvoidFerries?: bool|null,
     *   AvoidTolls?: bool|null,
     *   Dimensions?: TruckDimensions|array|null,
     *   Weight?: TruckWeight|array|null,
     * }|CalculateRouteTruckModeOptions $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAvoidFerries(): ?bool
    {
        return $this->avoidFerries;
    }

    public function getAvoidTolls(): ?bool
    {
        return $this->avoidTolls;
    }

    public function getDimensions(): ?TruckDimensions
    {
        return $this->dimensions;
    }

    public function getWeight(): ?TruckWeight
    {
        return $this->weight;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->avoidFerries) {
            $payload['AvoidFerries'] = (bool) $v;
        }
        if (null !== $v = $this->avoidTolls) {
            $payload['AvoidTolls'] = (bool) $v;
        }
        if (null !== $v = $this->dimensions) {
            $payload['Dimensions'] = $v->requestBody();
        }
        if (null !== $v = $this->weight) {
            $payload['Weight'] = $v->requestBody();
        }

        return $payload;
    }
}
