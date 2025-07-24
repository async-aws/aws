<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AvcIntraUhdQualityTuningLevel;

/**
 * Optional when you set AVC-Intra class to Class 4K/2K. When you set AVC-Intra class to a different value, this object
 * isn't allowed.
 */
final class AvcIntraUhdSettings
{
    /**
     * Optional. Use Quality tuning level to choose how many transcoding passes MediaConvert does with your video. When you
     * choose Multi-pass, your video quality is better and your output bitrate is more accurate. That is, the actual bitrate
     * of your output is closer to the target bitrate defined in the specification. When you choose Single-pass, your
     * encoding time is faster. The default behavior is Single-pass.
     *
     * @var AvcIntraUhdQualityTuningLevel::*|string|null
     */
    private $qualityTuningLevel;

    /**
     * @param array{
     *   QualityTuningLevel?: null|AvcIntraUhdQualityTuningLevel::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->qualityTuningLevel = $input['QualityTuningLevel'] ?? null;
    }

    /**
     * @param array{
     *   QualityTuningLevel?: null|AvcIntraUhdQualityTuningLevel::*|string,
     * }|AvcIntraUhdSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AvcIntraUhdQualityTuningLevel::*|string|null
     */
    public function getQualityTuningLevel(): ?string
    {
        return $this->qualityTuningLevel;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->qualityTuningLevel) {
            if (!AvcIntraUhdQualityTuningLevel::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "qualityTuningLevel" for "%s". The value "%s" is not a valid "AvcIntraUhdQualityTuningLevel".', __CLASS__, $v));
            }
            $payload['qualityTuningLevel'] = $v;
        }

        return $payload;
    }
}
