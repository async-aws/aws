<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings for a noise reducer filter.
 */
final class NoiseReducerFilterSettings
{
    /**
     * Relative strength of noise reducing filter. Higher values produce stronger filtering.
     */
    private $strength;

    /**
     * @param array{
     *   Strength?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->strength = $input['Strength'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->strength) {
            $payload['strength'] = $v;
        }

        return $payload;
    }
}
