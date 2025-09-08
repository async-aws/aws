<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use automated ABR to have MediaConvert set up the renditions in your ABR package for you automatically, based on
 * characteristics of your input video. This feature optimizes video quality while minimizing the overall size of your
 * ABR package.
 */
final class AutomatedAbrSettings
{
    /**
     * Specify the maximum average bitrate for MediaConvert to use in your automated ABR stack. If you don't specify a
     * value, MediaConvert uses 8,000,000 (8 mb/s) by default. The average bitrate of your highest-quality rendition will be
     * equal to or below this value, depending on the quality, complexity, and resolution of your content. Note that the
     * instantaneous maximum bitrate may vary above the value that you specify.
     *
     * @var int|null
     */
    private $maxAbrBitrate;

    /**
     * Optional. Specify the QVBR quality level to use for all renditions in your automated ABR stack. To have MediaConvert
     * automatically determine the quality level: Leave blank. To manually specify a quality level: Enter a value from 1 to
     * 10. MediaConvert will use a quality level up to the value that you specify, depending on your source. For more
     * information about QVBR quality levels, see: https://docs.aws.amazon.com/mediaconvert/latest/ug/qvbr-guidelines.html.
     *
     * @var float|null
     */
    private $maxQualityLevel;

    /**
     * Optional. The maximum number of renditions that MediaConvert will create in your automated ABR stack. The number of
     * renditions is determined automatically, based on analysis of each job, but will never exceed this limit. When you set
     * this to Auto in the console, which is equivalent to excluding it from your JSON job specification, MediaConvert
     * defaults to a limit of 15.
     *
     * @var int|null
     */
    private $maxRenditions;

    /**
     * Specify the minimum average bitrate for MediaConvert to use in your automated ABR stack. If you don't specify a
     * value, MediaConvert uses 600,000 (600 kb/s) by default. The average bitrate of your lowest-quality rendition will be
     * near this value. Note that the instantaneous minimum bitrate may vary below the value that you specify.
     *
     * @var int|null
     */
    private $minAbrBitrate;

    /**
     * Optional. Use Automated ABR rules to specify restrictions for the rendition sizes MediaConvert will create in your
     * ABR stack. You can use these rules if your ABR workflow has specific rendition size requirements, but you still want
     * MediaConvert to optimize for video quality and overall file size.
     *
     * @var AutomatedAbrRule[]|null
     */
    private $rules;

    /**
     * @param array{
     *   MaxAbrBitrate?: int|null,
     *   MaxQualityLevel?: float|null,
     *   MaxRenditions?: int|null,
     *   MinAbrBitrate?: int|null,
     *   Rules?: array<AutomatedAbrRule|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maxAbrBitrate = $input['MaxAbrBitrate'] ?? null;
        $this->maxQualityLevel = $input['MaxQualityLevel'] ?? null;
        $this->maxRenditions = $input['MaxRenditions'] ?? null;
        $this->minAbrBitrate = $input['MinAbrBitrate'] ?? null;
        $this->rules = isset($input['Rules']) ? array_map([AutomatedAbrRule::class, 'create'], $input['Rules']) : null;
    }

    /**
     * @param array{
     *   MaxAbrBitrate?: int|null,
     *   MaxQualityLevel?: float|null,
     *   MaxRenditions?: int|null,
     *   MinAbrBitrate?: int|null,
     *   Rules?: array<AutomatedAbrRule|array>|null,
     * }|AutomatedAbrSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxAbrBitrate(): ?int
    {
        return $this->maxAbrBitrate;
    }

    public function getMaxQualityLevel(): ?float
    {
        return $this->maxQualityLevel;
    }

    public function getMaxRenditions(): ?int
    {
        return $this->maxRenditions;
    }

    public function getMinAbrBitrate(): ?int
    {
        return $this->minAbrBitrate;
    }

    /**
     * @return AutomatedAbrRule[]
     */
    public function getRules(): array
    {
        return $this->rules ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maxAbrBitrate) {
            $payload['maxAbrBitrate'] = $v;
        }
        if (null !== $v = $this->maxQualityLevel) {
            $payload['maxQualityLevel'] = $v;
        }
        if (null !== $v = $this->maxRenditions) {
            $payload['maxRenditions'] = $v;
        }
        if (null !== $v = $this->minAbrBitrate) {
            $payload['minAbrBitrate'] = $v;
        }
        if (null !== $v = $this->rules) {
            $index = -1;
            $payload['rules'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['rules'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
