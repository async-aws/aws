<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use these settings when you set DolbyVisionLevel6Mode to SPECIFY to override the MaxCLL and MaxFALL values in your
 * input with new values.
 */
final class DolbyVisionLevel6Metadata
{
    /**
     * Maximum Content Light Level. Static HDR metadata that corresponds to the brightest pixel in the entire stream.
     * Measured in nits.
     *
     * @var int|null
     */
    private $maxCll;

    /**
     * Maximum Frame-Average Light Level. Static HDR metadata that corresponds to the highest frame-average brightness in
     * the entire stream. Measured in nits.
     *
     * @var int|null
     */
    private $maxFall;

    /**
     * @param array{
     *   MaxCll?: int|null,
     *   MaxFall?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maxCll = $input['MaxCll'] ?? null;
        $this->maxFall = $input['MaxFall'] ?? null;
    }

    /**
     * @param array{
     *   MaxCll?: int|null,
     *   MaxFall?: int|null,
     * }|DolbyVisionLevel6Metadata $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxCll(): ?int
    {
        return $this->maxCll;
    }

    public function getMaxFall(): ?int
    {
        return $this->maxFall;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maxCll) {
            $payload['maxCll'] = $v;
        }
        if (null !== $v = $this->maxFall) {
            $payload['maxFall'] = $v;
        }

        return $payload;
    }
}
