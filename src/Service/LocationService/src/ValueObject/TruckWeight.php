<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\LocationService\Enum\VehicleWeightUnit;

/**
 * Contains details about the truck's weight specifications. Used to avoid roads that can't support or allow the total
 * weight for requests that specify `TravelMode` as `Truck`.
 */
final class TruckWeight
{
    /**
     * The total weight of the truck.
     *
     * - For example, `3500`.
     *
     * @var float|null
     */
    private $total;

    /**
     * The unit of measurement to use for the truck weight.
     *
     * Default Value: `Kilograms`
     *
     * @var VehicleWeightUnit::*|null
     */
    private $unit;

    /**
     * @param array{
     *   Total?: float|null,
     *   Unit?: VehicleWeightUnit::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->total = $input['Total'] ?? null;
        $this->unit = $input['Unit'] ?? null;
    }

    /**
     * @param array{
     *   Total?: float|null,
     *   Unit?: VehicleWeightUnit::*|null,
     * }|TruckWeight $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @return VehicleWeightUnit::*|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->total) {
            $payload['Total'] = $v;
        }
        if (null !== $v = $this->unit) {
            if (!VehicleWeightUnit::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "Unit" for "%s". The value "%s" is not a valid "VehicleWeightUnit".', __CLASS__, $v));
            }
            $payload['Unit'] = $v;
        }

        return $payload;
    }
}
