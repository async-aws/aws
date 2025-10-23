<?php

namespace AsyncAws\LocationService\ValueObject;

/**
 * Contains details about additional route preferences for requests that specify `TravelMode` as `Car`.
 */
final class CalculateRouteCarModeOptions
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
     * @param array{
     *   AvoidFerries?: bool|null,
     *   AvoidTolls?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->avoidFerries = $input['AvoidFerries'] ?? null;
        $this->avoidTolls = $input['AvoidTolls'] ?? null;
    }

    /**
     * @param array{
     *   AvoidFerries?: bool|null,
     *   AvoidTolls?: bool|null,
     * }|CalculateRouteCarModeOptions $input
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

        return $payload;
    }
}
