<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\NoiseFilterPostTemporalSharpening;
use AsyncAws\MediaConvert\Enum\NoiseFilterPostTemporalSharpeningStrength;

/**
 * Noise reducer filter settings for temporal filter.
 */
final class NoiseReducerTemporalFilterSettings
{
    /**
     * Use Aggressive mode for content that has complex motion. Higher values produce stronger temporal filtering. This
     * filters highly complex scenes more aggressively and creates better VQ for low bitrate outputs.
     *
     * @var int|null
     */
    private $aggressiveMode;

    /**
     * When you set Noise reducer to Temporal, the bandwidth and sharpness of your output is reduced. You can optionally use
     * Post temporal sharpening to apply sharpening to the edges of your output. Note that Post temporal sharpening will
     * also make the bandwidth reduction from the Noise reducer smaller. The default behavior, Auto, allows the transcoder
     * to determine whether to apply sharpening, depending on your input type and quality. When you set Post temporal
     * sharpening to Enabled, specify how much sharpening is applied using Post temporal sharpening strength. Set Post
     * temporal sharpening to Disabled to not apply sharpening.
     *
     * @var NoiseFilterPostTemporalSharpening::*|null
     */
    private $postTemporalSharpening;

    /**
     * Use Post temporal sharpening strength to define the amount of sharpening the transcoder applies to your output. Set
     * Post temporal sharpening strength to Low, Medium, or High to indicate the amount of sharpening.
     *
     * @var NoiseFilterPostTemporalSharpeningStrength::*|null
     */
    private $postTemporalSharpeningStrength;

    /**
     * The speed of the filter (higher number is faster). Low setting reduces bit rate at the cost of transcode time, high
     * setting improves transcode time at the cost of bit rate.
     *
     * @var int|null
     */
    private $speed;

    /**
     * Specify the strength of the noise reducing filter on this output. Higher values produce stronger filtering. We
     * recommend the following value ranges, depending on the result that you want: * 0-2 for complexity reduction with
     * minimal sharpness loss * 2-8 for complexity reduction with image preservation * 8-16 for a high level of complexity
     * reduction.
     *
     * @var int|null
     */
    private $strength;

    /**
     * @param array{
     *   AggressiveMode?: int|null,
     *   PostTemporalSharpening?: NoiseFilterPostTemporalSharpening::*|null,
     *   PostTemporalSharpeningStrength?: NoiseFilterPostTemporalSharpeningStrength::*|null,
     *   Speed?: int|null,
     *   Strength?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->aggressiveMode = $input['AggressiveMode'] ?? null;
        $this->postTemporalSharpening = $input['PostTemporalSharpening'] ?? null;
        $this->postTemporalSharpeningStrength = $input['PostTemporalSharpeningStrength'] ?? null;
        $this->speed = $input['Speed'] ?? null;
        $this->strength = $input['Strength'] ?? null;
    }

    /**
     * @param array{
     *   AggressiveMode?: int|null,
     *   PostTemporalSharpening?: NoiseFilterPostTemporalSharpening::*|null,
     *   PostTemporalSharpeningStrength?: NoiseFilterPostTemporalSharpeningStrength::*|null,
     *   Speed?: int|null,
     *   Strength?: int|null,
     * }|NoiseReducerTemporalFilterSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAggressiveMode(): ?int
    {
        return $this->aggressiveMode;
    }

    /**
     * @return NoiseFilterPostTemporalSharpening::*|null
     */
    public function getPostTemporalSharpening(): ?string
    {
        return $this->postTemporalSharpening;
    }

    /**
     * @return NoiseFilterPostTemporalSharpeningStrength::*|null
     */
    public function getPostTemporalSharpeningStrength(): ?string
    {
        return $this->postTemporalSharpeningStrength;
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
        if (null !== $v = $this->aggressiveMode) {
            $payload['aggressiveMode'] = $v;
        }
        if (null !== $v = $this->postTemporalSharpening) {
            if (!NoiseFilterPostTemporalSharpening::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "postTemporalSharpening" for "%s". The value "%s" is not a valid "NoiseFilterPostTemporalSharpening".', __CLASS__, $v));
            }
            $payload['postTemporalSharpening'] = $v;
        }
        if (null !== $v = $this->postTemporalSharpeningStrength) {
            if (!NoiseFilterPostTemporalSharpeningStrength::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "postTemporalSharpeningStrength" for "%s". The value "%s" is not a valid "NoiseFilterPostTemporalSharpeningStrength".', __CLASS__, $v));
            }
            $payload['postTemporalSharpeningStrength'] = $v;
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
