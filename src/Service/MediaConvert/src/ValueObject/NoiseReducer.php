<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\NoiseReducerFilter;

/**
 * Enable the Noise reducer feature to remove noise from your video output if necessary. Enable or disable this feature
 * for each output individually. This setting is disabled by default. When you enable Noise reducer, you must also
 * select a value for Noise reducer filter. For AVC outputs, when you include Noise reducer, you cannot include the
 * Bandwidth reduction filter.
 */
final class NoiseReducer
{
    /**
     * Use Noise reducer filter to select one of the following spatial image filtering functions. To use this setting, you
     * must also enable Noise reducer. * Bilateral preserves edges while reducing noise. * Mean (softest), Gaussian,
     * Lanczos, and Sharpen (sharpest) do convolution filtering. * Conserve does min/max noise reduction. * Spatial does
     * frequency-domain filtering based on JND principles. * Temporal optimizes video quality for complex motion.
     *
     * @var NoiseReducerFilter::*|string|null
     */
    private $filter;

    /**
     * Settings for a noise reducer filter.
     *
     * @var NoiseReducerFilterSettings|null
     */
    private $filterSettings;

    /**
     * Noise reducer filter settings for spatial filter.
     *
     * @var NoiseReducerSpatialFilterSettings|null
     */
    private $spatialFilterSettings;

    /**
     * Noise reducer filter settings for temporal filter.
     *
     * @var NoiseReducerTemporalFilterSettings|null
     */
    private $temporalFilterSettings;

    /**
     * @param array{
     *   Filter?: null|NoiseReducerFilter::*|string,
     *   FilterSettings?: null|NoiseReducerFilterSettings|array,
     *   SpatialFilterSettings?: null|NoiseReducerSpatialFilterSettings|array,
     *   TemporalFilterSettings?: null|NoiseReducerTemporalFilterSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->filter = $input['Filter'] ?? null;
        $this->filterSettings = isset($input['FilterSettings']) ? NoiseReducerFilterSettings::create($input['FilterSettings']) : null;
        $this->spatialFilterSettings = isset($input['SpatialFilterSettings']) ? NoiseReducerSpatialFilterSettings::create($input['SpatialFilterSettings']) : null;
        $this->temporalFilterSettings = isset($input['TemporalFilterSettings']) ? NoiseReducerTemporalFilterSettings::create($input['TemporalFilterSettings']) : null;
    }

    /**
     * @param array{
     *   Filter?: null|NoiseReducerFilter::*|string,
     *   FilterSettings?: null|NoiseReducerFilterSettings|array,
     *   SpatialFilterSettings?: null|NoiseReducerSpatialFilterSettings|array,
     *   TemporalFilterSettings?: null|NoiseReducerTemporalFilterSettings|array,
     * }|NoiseReducer $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return NoiseReducerFilter::*|string|null
     */
    public function getFilter(): ?string
    {
        return $this->filter;
    }

    public function getFilterSettings(): ?NoiseReducerFilterSettings
    {
        return $this->filterSettings;
    }

    public function getSpatialFilterSettings(): ?NoiseReducerSpatialFilterSettings
    {
        return $this->spatialFilterSettings;
    }

    public function getTemporalFilterSettings(): ?NoiseReducerTemporalFilterSettings
    {
        return $this->temporalFilterSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->filter) {
            if (!NoiseReducerFilter::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "filter" for "%s". The value "%s" is not a valid "NoiseReducerFilter".', __CLASS__, $v));
            }
            $payload['filter'] = $v;
        }
        if (null !== $v = $this->filterSettings) {
            $payload['filterSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->spatialFilterSettings) {
            $payload['spatialFilterSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->temporalFilterSettings) {
            $payload['temporalFilterSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
