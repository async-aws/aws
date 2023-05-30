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
     * Optional. The maximum target bit rate used in your automated ABR stack. Use this value to set an upper limit on the
     * bandwidth consumed by the highest-quality rendition. This is the rendition that is delivered to viewers with the
     * fastest internet connections. If you don't specify a value, MediaConvert uses 8,000,000 (8 mb/s) by default.
     */
    private $maxAbrBitrate;

    /**
     * Optional. The maximum number of renditions that MediaConvert will create in your automated ABR stack. The number of
     * renditions is determined automatically, based on analysis of each job, but will never exceed this limit. When you set
     * this to Auto in the console, which is equivalent to excluding it from your JSON job specification, MediaConvert
     * defaults to a limit of 15.
     */
    private $maxRenditions;

    /**
     * Optional. The minimum target bitrate used in your automated ABR stack. Use this value to set a lower limit on the
     * bitrate of video delivered to viewers with slow internet connections. If you don't specify a value, MediaConvert uses
     * 600,000 (600 kb/s) by default.
     */
    private $minAbrBitrate;

    /**
     * Optional. Use Automated ABR rules to specify restrictions for the rendition sizes MediaConvert will create in your
     * ABR stack. You can use these rules if your ABR workflow has specific rendition size requirements, but you still want
     * MediaConvert to optimize for video quality and overall file size.
     */
    private $rules;

    /**
     * @param array{
     *   MaxAbrBitrate?: null|int,
     *   MaxRenditions?: null|int,
     *   MinAbrBitrate?: null|int,
     *   Rules?: null|AutomatedAbrRule[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maxAbrBitrate = $input['MaxAbrBitrate'] ?? null;
        $this->maxRenditions = $input['MaxRenditions'] ?? null;
        $this->minAbrBitrate = $input['MinAbrBitrate'] ?? null;
        $this->rules = isset($input['Rules']) ? array_map([AutomatedAbrRule::class, 'create'], $input['Rules']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxAbrBitrate(): ?int
    {
        return $this->maxAbrBitrate;
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
