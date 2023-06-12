<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Noise reducer filter settings for spatial filter.
 */
final class NoiseReducerSpatialFilterSettings
{
    /**
     * Specify strength of post noise reduction sharpening filter, with 0 disabling the filter and 3 enabling it at maximum
     * strength.
     */
    private $postFilterSharpenStrength;

    /**
     * The speed of the filter, from -2 (lower speed) to 3 (higher speed), with 0 being the nominal value.
     */
    private $speed;

    /**
     * Relative strength of noise reducing filter. Higher values produce stronger filtering.
     */
    private $strength;

    /**
     * @param array{
     *   PostFilterSharpenStrength?: null|int,
     *   Speed?: null|int,
     *   Strength?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->postFilterSharpenStrength = $input['PostFilterSharpenStrength'] ?? null;
        $this->speed = $input['Speed'] ?? null;
        $this->strength = $input['Strength'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPostFilterSharpenStrength(): ?int
    {
        return $this->postFilterSharpenStrength;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
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
        if (null !== $v = $this->postFilterSharpenStrength) {
            $payload['postFilterSharpenStrength'] = $v;
        }
        if (null !== $v = $this->speed) {
            $payload['speed'] = $v;
        }
        if (null !== $v = $this->strength) {
            $payload['strength'] = $v;
        }

        return $payload;
    }
}
