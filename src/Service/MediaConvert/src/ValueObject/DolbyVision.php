<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\DolbyVisionCompatibility;
use AsyncAws\MediaConvert\Enum\DolbyVisionLevel6Mode;
use AsyncAws\MediaConvert\Enum\DolbyVisionMapping;
use AsyncAws\MediaConvert\Enum\DolbyVisionProfile;

/**
 * Create Dolby Vision Profile 5 or Profile 8.1 compatible video output.
 */
final class DolbyVision
{
    /**
     * When you set Compatibility mapping to Duplicate Stream, DolbyVision streams that have a backward compatible base
     * layer (e.g., DolbyVision 8.1) will cause a duplicate stream to be signaled in the manifest as a duplicate stream.
     * When you set Compatibility mapping to Supplemntal Codecs, DolbyVision streams that have a backward compatible base
     * layer (e.g., DolbyVision 8.1) will cause the associate stream in the manifest to include a SUPPLEMENTAL_CODECS
     * property.
     *
     * @var DolbyVisionCompatibility::*|null
     */
    private $compatibility;

    /**
     * Use these settings when you set DolbyVisionLevel6Mode to SPECIFY to override the MaxCLL and MaxFALL values in your
     * input with new values.
     *
     * @var DolbyVisionLevel6Metadata|null
     */
    private $l6Metadata;

    /**
     * Use Dolby Vision Mode to choose how the service will handle Dolby Vision MaxCLL and MaxFALL properies.
     *
     * @var DolbyVisionLevel6Mode::*|null
     */
    private $l6Mode;

    /**
     * Required when you set Dolby Vision Profile to Profile 8.1. When you set Content mapping to None, content mapping is
     * not applied to the HDR10-compatible signal. Depending on the source peak nit level, clipping might occur on HDR
     * devices without Dolby Vision. When you set Content mapping to HDR10 1000, the transcoder creates a 1,000 nits peak
     * HDR10-compatible signal by applying static content mapping to the source. This mode is speed-optimized for PQ10
     * sources with metadata that is created from analysis. For graded Dolby Vision content, be aware that creative intent
     * might not be guaranteed with extreme 1,000 nits trims.
     *
     * @var DolbyVisionMapping::*|null
     */
    private $mapping;

    /**
     * Required when you enable Dolby Vision. Use Profile 5 to include frame-interleaved Dolby Vision metadata in your
     * output. Your input must include Dolby Vision metadata or an HDR10 YUV color space. Use Profile 8.1 to include
     * frame-interleaved Dolby Vision metadata and HDR10 metadata in your output. Your input must include Dolby Vision
     * metadata.
     *
     * @var DolbyVisionProfile::*|null
     */
    private $profile;

    /**
     * @param array{
     *   Compatibility?: DolbyVisionCompatibility::*|null,
     *   L6Metadata?: DolbyVisionLevel6Metadata|array|null,
     *   L6Mode?: DolbyVisionLevel6Mode::*|null,
     *   Mapping?: DolbyVisionMapping::*|null,
     *   Profile?: DolbyVisionProfile::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->compatibility = $input['Compatibility'] ?? null;
        $this->l6Metadata = isset($input['L6Metadata']) ? DolbyVisionLevel6Metadata::create($input['L6Metadata']) : null;
        $this->l6Mode = $input['L6Mode'] ?? null;
        $this->mapping = $input['Mapping'] ?? null;
        $this->profile = $input['Profile'] ?? null;
    }

    /**
     * @param array{
     *   Compatibility?: DolbyVisionCompatibility::*|null,
     *   L6Metadata?: DolbyVisionLevel6Metadata|array|null,
     *   L6Mode?: DolbyVisionLevel6Mode::*|null,
     *   Mapping?: DolbyVisionMapping::*|null,
     *   Profile?: DolbyVisionProfile::*|null,
     * }|DolbyVision $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DolbyVisionCompatibility::*|null
     */
    public function getCompatibility(): ?string
    {
        return $this->compatibility;
    }

    public function getL6Metadata(): ?DolbyVisionLevel6Metadata
    {
        return $this->l6Metadata;
    }

    /**
     * @return DolbyVisionLevel6Mode::*|null
     */
    public function getL6Mode(): ?string
    {
        return $this->l6Mode;
    }

    /**
     * @return DolbyVisionMapping::*|null
     */
    public function getMapping(): ?string
    {
        return $this->mapping;
    }

    /**
     * @return DolbyVisionProfile::*|null
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->compatibility) {
            if (!DolbyVisionCompatibility::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "compatibility" for "%s". The value "%s" is not a valid "DolbyVisionCompatibility".', __CLASS__, $v));
            }
            $payload['compatibility'] = $v;
        }
        if (null !== $v = $this->l6Metadata) {
            $payload['l6Metadata'] = $v->requestBody();
        }
        if (null !== $v = $this->l6Mode) {
            if (!DolbyVisionLevel6Mode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "l6Mode" for "%s". The value "%s" is not a valid "DolbyVisionLevel6Mode".', __CLASS__, $v));
            }
            $payload['l6Mode'] = $v;
        }
        if (null !== $v = $this->mapping) {
            if (!DolbyVisionMapping::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "mapping" for "%s". The value "%s" is not a valid "DolbyVisionMapping".', __CLASS__, $v));
            }
            $payload['mapping'] = $v;
        }
        if (null !== $v = $this->profile) {
            if (!DolbyVisionProfile::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "profile" for "%s". The value "%s" is not a valid "DolbyVisionProfile".', __CLASS__, $v));
            }
            $payload['profile'] = $v;
        }

        return $payload;
    }
}
