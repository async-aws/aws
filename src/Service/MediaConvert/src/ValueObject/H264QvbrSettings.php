<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings for quality-defined variable bitrate encoding with the H.264 codec. Use these settings only when you set
 * QVBR for Rate control mode.
 */
final class H264QvbrSettings
{
    /**
     * Use this setting only when Rate control mode is QVBR and Quality tuning level is Multi-pass HQ. For Max average
     * bitrate values suited to the complexity of your input video, the service limits the average bitrate of the video part
     * of this output to the value that you choose. That is, the total size of the video element is less than or equal to
     * the value you set multiplied by the number of seconds of encoded output.
     *
     * @var int|null
     */
    private $maxAverageBitrate;

    /**
     * Use this setting only when you set Rate control mode to QVBR. Specify the target quality level for this output.
     * MediaConvert determines the right number of bits to use for each part of the video to maintain the video quality that
     * you specify. When you keep the default value, AUTO, MediaConvert picks a quality level for you, based on
     * characteristics of your input video. If you prefer to specify a quality level, specify a number from 1 through 10.
     * Use higher numbers for greater quality. Level 10 results in nearly lossless compression. The quality level for most
     * broadcast-quality transcodes is between 6 and 9. Optionally, to specify a value between whole numbers, also provide a
     * value for the setting qvbrQualityLevelFineTune. For example, if you want your QVBR quality level to be 7.33, set
     * qvbrQualityLevel to 7 and set qvbrQualityLevelFineTune to .33.
     *
     * @var int|null
     */
    private $qvbrQualityLevel;

    /**
     * Optional. Specify a value here to set the QVBR quality to a level that is between whole numbers. For example, if you
     * want your QVBR quality level to be 7.33, set qvbrQualityLevel to 7 and set qvbrQualityLevelFineTune to .33.
     * MediaConvert rounds your QVBR quality level to the nearest third of a whole number. For example, if you set
     * qvbrQualityLevel to 7 and you set qvbrQualityLevelFineTune to .25, your actual QVBR quality level is 7.33.
     *
     * @var float|null
     */
    private $qvbrQualityLevelFineTune;

    /**
     * @param array{
     *   MaxAverageBitrate?: int|null,
     *   QvbrQualityLevel?: int|null,
     *   QvbrQualityLevelFineTune?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maxAverageBitrate = $input['MaxAverageBitrate'] ?? null;
        $this->qvbrQualityLevel = $input['QvbrQualityLevel'] ?? null;
        $this->qvbrQualityLevelFineTune = $input['QvbrQualityLevelFineTune'] ?? null;
    }

    /**
     * @param array{
     *   MaxAverageBitrate?: int|null,
     *   QvbrQualityLevel?: int|null,
     *   QvbrQualityLevelFineTune?: float|null,
     * }|H264QvbrSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxAverageBitrate(): ?int
    {
        return $this->maxAverageBitrate;
    }

    public function getQvbrQualityLevel(): ?int
    {
        return $this->qvbrQualityLevel;
    }

    public function getQvbrQualityLevelFineTune(): ?float
    {
        return $this->qvbrQualityLevelFineTune;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maxAverageBitrate) {
            $payload['maxAverageBitrate'] = $v;
        }
        if (null !== $v = $this->qvbrQualityLevel) {
            $payload['qvbrQualityLevel'] = $v;
        }
        if (null !== $v = $this->qvbrQualityLevelFineTune) {
            $payload['qvbrQualityLevelFineTune'] = $v;
        }

        return $payload;
    }
}
