<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Required when you set Codec to the value FRAME_CAPTURE.
 */
final class FrameCaptureSettings
{
    /**
     * Frame capture will encode the first frame of the output stream, then one frame every
     * framerateDenominator/framerateNumerator seconds. For example, settings of framerateNumerator = 1 and
     * framerateDenominator = 3 (a rate of 1/3 frame per second) will capture the first frame, then 1 frame every 3s. Files
     * will be named as filename.n.jpg where n is the 0-based sequence number of each Capture.
     *
     * @var int|null
     */
    private $framerateDenominator;

    /**
     * Frame capture will encode the first frame of the output stream, then one frame every
     * framerateDenominator/framerateNumerator seconds. For example, settings of framerateNumerator = 1 and
     * framerateDenominator = 3 (a rate of 1/3 frame per second) will capture the first frame, then 1 frame every 3s. Files
     * will be named as filename.NNNNNNN.jpg where N is the 0-based frame sequence number zero padded to 7 decimal places.
     *
     * @var int|null
     */
    private $framerateNumerator;

    /**
     * Maximum number of captures (encoded jpg output files).
     *
     * @var int|null
     */
    private $maxCaptures;

    /**
     * JPEG Quality - a higher value equals higher quality.
     *
     * @var int|null
     */
    private $quality;

    /**
     * @param array{
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     *   MaxCaptures?: int|null,
     *   Quality?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->maxCaptures = $input['MaxCaptures'] ?? null;
        $this->quality = $input['Quality'] ?? null;
    }

    /**
     * @param array{
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     *   MaxCaptures?: int|null,
     *   Quality?: int|null,
     * }|FrameCaptureSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFramerateDenominator(): ?int
    {
        return $this->framerateDenominator;
    }

    public function getFramerateNumerator(): ?int
    {
        return $this->framerateNumerator;
    }

    public function getMaxCaptures(): ?int
    {
        return $this->maxCaptures;
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->framerateDenominator) {
            $payload['framerateDenominator'] = $v;
        }
        if (null !== $v = $this->framerateNumerator) {
            $payload['framerateNumerator'] = $v;
        }
        if (null !== $v = $this->maxCaptures) {
            $payload['maxCaptures'] = $v;
        }
        if (null !== $v = $this->quality) {
            $payload['quality'] = $v;
        }

        return $payload;
    }
}
