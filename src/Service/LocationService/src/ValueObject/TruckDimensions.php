<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\LocationService\Enum\DimensionUnit;

/**
 * Contains details about the truck dimensions in the unit of measurement that you specify. Used to filter out roads
 * that can't support or allow the specified dimensions for requests that specify `TravelMode` as `Truck`.
 */
final class TruckDimensions
{
    /**
     * The height of the truck.
     *
     * - For example, `4.5`.
     *
     * > For routes calculated with a HERE resource, this value must be between 0 and 50 meters.
     *
     * @var float|null
     */
    private $height;

    /**
     * The length of the truck.
     *
     * - For example, `15.5`.
     *
     * > For routes calculated with a HERE resource, this value must be between 0 and 300 meters.
     *
     * @var float|null
     */
    private $length;

    /**
     * Specifies the unit of measurement for the truck dimensions.
     *
     * Default Value: `Meters`
     *
     * @var DimensionUnit::*|null
     */
    private $unit;

    /**
     * The width of the truck.
     *
     * - For example, `4.5`.
     *
     * > For routes calculated with a HERE resource, this value must be between 0 and 50 meters.
     *
     * @var float|null
     */
    private $width;

    /**
     * @param array{
     *   Height?: null|float,
     *   Length?: null|float,
     *   Unit?: null|DimensionUnit::*,
     *   Width?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->height = $input['Height'] ?? null;
        $this->length = $input['Length'] ?? null;
        $this->unit = $input['Unit'] ?? null;
        $this->width = $input['Width'] ?? null;
    }

    /**
     * @param array{
     *   Height?: null|float,
     *   Length?: null|float,
     *   Unit?: null|DimensionUnit::*,
     *   Width?: null|float,
     * }|TruckDimensions $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    /**
     * @return DimensionUnit::*|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->height) {
            $payload['Height'] = $v;
        }
        if (null !== $v = $this->length) {
            $payload['Length'] = $v;
        }
        if (null !== $v = $this->unit) {
            if (!DimensionUnit::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Unit" for "%s". The value "%s" is not a valid "DimensionUnit".', __CLASS__, $v));
            }
            $payload['Unit'] = $v;
        }
        if (null !== $v = $this->width) {
            $payload['Width'] = $v;
        }

        return $payload;
    }
}
