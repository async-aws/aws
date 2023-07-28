<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Av1AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\Av1BitDepth;
use AsyncAws\MediaConvert\Enum\Av1FramerateControl;
use AsyncAws\MediaConvert\Enum\Av1FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Av1RateControlMode;
use AsyncAws\MediaConvert\Enum\Av1SpatialAdaptiveQuantization;

/**
 * Required when you set Codec, under VideoDescription>CodecSettings to the value AV1.
 */
final class Av1Settings
{
    /**
     * Specify the strength of any adaptive quantization filters that you enable. The value that you choose here applies to
     * Spatial adaptive quantization.
     *
     * @var Av1AdaptiveQuantization::*|null
     */
    private $adaptiveQuantization;

    /**
     * Specify the Bit depth. You can choose 8-bit or 10-bit.
     *
     * @var Av1BitDepth::*|null
     */
    private $bitDepth;

    /**
     * Use the Framerate setting to specify the frame rate for this output. If you want to keep the same frame rate as the
     * input video, choose Follow source. If you want to do frame rate conversion, choose a frame rate from the dropdown
     * list or choose Custom. The framerates shown in the dropdown list are decimal approximations of fractions. If you
     * choose Custom, specify your frame rate as a fraction.
     *
     * @var Av1FramerateControl::*|null
     */
    private $framerateControl;

    /**
     * Choose the method that you want MediaConvert to use when increasing or decreasing the frame rate. For numerically
     * simple conversions, such as 60 fps to 30 fps: We recommend that you keep the default value, Drop duplicate. For
     * numerically complex conversions, to avoid stutter: Choose Interpolate. This results in a smooth picture, but might
     * introduce undesirable video artifacts. For complex frame rate conversions, especially if your source video has
     * already been converted from its original cadence: Choose FrameFormer to do motion-compensated interpolation.
     * FrameFormer uses the best conversion method frame by frame. Note that using FrameFormer increases the transcoding
     * time and incurs a significant add-on cost. When you choose FrameFormer, your input video resolution must be at least
     * 128x96.
     *
     * @var Av1FramerateConversionAlgorithm::*|null
     */
    private $framerateConversionAlgorithm;

    /**
     * When you use the API for transcode jobs that use frame rate conversion, specify the frame rate as a fraction. For
     * example, 24000 / 1001 = 23.976 fps. Use FramerateDenominator to specify the denominator of this fraction. In this
     * example, use 1001 for the value of FramerateDenominator. When you use the console for transcode jobs that use frame
     * rate conversion, provide the value as a decimal number for Framerate. In this example, specify 23.976.
     *
     * @var int|null
     */
    private $framerateDenominator;

    /**
     * When you use the API for transcode jobs that use frame rate conversion, specify the frame rate as a fraction. For
     * example, 24000 / 1001 = 23.976 fps. Use FramerateNumerator to specify the numerator of this fraction. In this
     * example, use 24000 for the value of FramerateNumerator. When you use the console for transcode jobs that use frame
     * rate conversion, provide the value as a decimal number for Framerate. In this example, specify 23.976.
     *
     * @var int|null
     */
    private $framerateNumerator;

    /**
     * Specify the GOP length (keyframe interval) in frames. With AV1, MediaConvert doesn't support GOP length in seconds.
     * This value must be greater than zero and preferably equal to 1 + ((numberBFrames + 1) * x), where x is an integer
     * value.
     *
     * @var float|null
     */
    private $gopSize;

    /**
     * Maximum bitrate in bits/second. For example, enter five megabits per second as 5000000. Required when Rate control
     * mode is QVBR.
     *
     * @var int|null
     */
    private $maxBitrate;

    /**
     * Specify from the number of B-frames, in the range of 0-15. For AV1 encoding, we recommend using 7 or 15. Choose a
     * larger number for a lower bitrate and smaller file size; choose a smaller number for better video quality.
     *
     * @var int|null
     */
    private $numberBframesBetweenReferenceFrames;

    /**
     * Settings for quality-defined variable bitrate encoding with the H.265 codec. Use these settings only when you set
     * QVBR for Rate control mode.
     *
     * @var Av1QvbrSettings|null
     */
    private $qvbrSettings;

    /**
     * 'With AV1 outputs, for rate control mode, MediaConvert supports only quality-defined variable bitrate (QVBR). You
     * can''t use CBR or VBR.'.
     *
     * @var Av1RateControlMode::*|null
     */
    private $rateControlMode;

    /**
     * Specify the number of slices per picture. This value must be 1, 2, 4, 8, 16, or 32. For progressive pictures, this
     * value must be less than or equal to the number of macroblock rows. For interlaced pictures, this value must be less
     * than or equal to half the number of macroblock rows.
     *
     * @var int|null
     */
    private $slices;

    /**
     * Keep the default value, Enabled, to adjust quantization within each frame based on spatial variation of content
     * complexity. When you enable this feature, the encoder uses fewer bits on areas that can sustain more distortion with
     * no noticeable visual degradation and uses more bits on areas where any small distortion will be noticeable. For
     * example, complex textured blocks are encoded with fewer bits and smooth textured blocks are encoded with more bits.
     * Enabling this feature will almost always improve your video quality. Note, though, that this feature doesn't take
     * into account where the viewer's attention is likely to be. If viewers are likely to be focusing their attention on a
     * part of the screen with a lot of complex texture, you might choose to disable this feature. Related setting: When you
     * enable spatial adaptive quantization, set the value for Adaptive quantization depending on your content. For
     * homogeneous content, such as cartoons and video games, set it to Low. For content with a wider variety of textures,
     * set it to High or Higher.
     *
     * @var Av1SpatialAdaptiveQuantization::*|null
     */
    private $spatialAdaptiveQuantization;

    /**
     * @param array{
     *   AdaptiveQuantization?: null|Av1AdaptiveQuantization::*,
     *   BitDepth?: null|Av1BitDepth::*,
     *   FramerateControl?: null|Av1FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|Av1FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopSize?: null|float,
     *   MaxBitrate?: null|int,
     *   NumberBFramesBetweenReferenceFrames?: null|int,
     *   QvbrSettings?: null|Av1QvbrSettings|array,
     *   RateControlMode?: null|Av1RateControlMode::*,
     *   Slices?: null|int,
     *   SpatialAdaptiveQuantization?: null|Av1SpatialAdaptiveQuantization::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->adaptiveQuantization = $input['AdaptiveQuantization'] ?? null;
        $this->bitDepth = $input['BitDepth'] ?? null;
        $this->framerateControl = $input['FramerateControl'] ?? null;
        $this->framerateConversionAlgorithm = $input['FramerateConversionAlgorithm'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->gopSize = $input['GopSize'] ?? null;
        $this->maxBitrate = $input['MaxBitrate'] ?? null;
        $this->numberBframesBetweenReferenceFrames = $input['NumberBFramesBetweenReferenceFrames'] ?? null;
        $this->qvbrSettings = isset($input['QvbrSettings']) ? Av1QvbrSettings::create($input['QvbrSettings']) : null;
        $this->rateControlMode = $input['RateControlMode'] ?? null;
        $this->slices = $input['Slices'] ?? null;
        $this->spatialAdaptiveQuantization = $input['SpatialAdaptiveQuantization'] ?? null;
    }

    /**
     * @param array{
     *   AdaptiveQuantization?: null|Av1AdaptiveQuantization::*,
     *   BitDepth?: null|Av1BitDepth::*,
     *   FramerateControl?: null|Av1FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|Av1FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopSize?: null|float,
     *   MaxBitrate?: null|int,
     *   NumberBFramesBetweenReferenceFrames?: null|int,
     *   QvbrSettings?: null|Av1QvbrSettings|array,
     *   RateControlMode?: null|Av1RateControlMode::*,
     *   Slices?: null|int,
     *   SpatialAdaptiveQuantization?: null|Av1SpatialAdaptiveQuantization::*,
     * }|Av1Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Av1AdaptiveQuantization::*|null
     */
    public function getAdaptiveQuantization(): ?string
    {
        return $this->adaptiveQuantization;
    }

    /**
     * @return Av1BitDepth::*|null
     */
    public function getBitDepth(): ?string
    {
        return $this->bitDepth;
    }

    /**
     * @return Av1FramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return Av1FramerateConversionAlgorithm::*|null
     */
    public function getFramerateConversionAlgorithm(): ?string
    {
        return $this->framerateConversionAlgorithm;
    }

    public function getFramerateDenominator(): ?int
    {
        return $this->framerateDenominator;
    }

    public function getFramerateNumerator(): ?int
    {
        return $this->framerateNumerator;
    }

    public function getGopSize(): ?float
    {
        return $this->gopSize;
    }

    public function getMaxBitrate(): ?int
    {
        return $this->maxBitrate;
    }

    public function getNumberBframesBetweenReferenceFrames(): ?int
    {
        return $this->numberBframesBetweenReferenceFrames;
    }

    public function getQvbrSettings(): ?Av1QvbrSettings
    {
        return $this->qvbrSettings;
    }

    /**
     * @return Av1RateControlMode::*|null
     */
    public function getRateControlMode(): ?string
    {
        return $this->rateControlMode;
    }

    public function getSlices(): ?int
    {
        return $this->slices;
    }

    /**
     * @return Av1SpatialAdaptiveQuantization::*|null
     */
    public function getSpatialAdaptiveQuantization(): ?string
    {
        return $this->spatialAdaptiveQuantization;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->adaptiveQuantization) {
            if (!Av1AdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "adaptiveQuantization" for "%s". The value "%s" is not a valid "Av1AdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['adaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->bitDepth) {
            if (!Av1BitDepth::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "bitDepth" for "%s". The value "%s" is not a valid "Av1BitDepth".', __CLASS__, $v));
            }
            $payload['bitDepth'] = $v;
        }
        if (null !== $v = $this->framerateControl) {
            if (!Av1FramerateControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "Av1FramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!Av1FramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "Av1FramerateConversionAlgorithm".', __CLASS__, $v));
            }
            $payload['framerateConversionAlgorithm'] = $v;
        }
        if (null !== $v = $this->framerateDenominator) {
            $payload['framerateDenominator'] = $v;
        }
        if (null !== $v = $this->framerateNumerator) {
            $payload['framerateNumerator'] = $v;
        }
        if (null !== $v = $this->gopSize) {
            $payload['gopSize'] = $v;
        }
        if (null !== $v = $this->maxBitrate) {
            $payload['maxBitrate'] = $v;
        }
        if (null !== $v = $this->numberBframesBetweenReferenceFrames) {
            $payload['numberBFramesBetweenReferenceFrames'] = $v;
        }
        if (null !== $v = $this->qvbrSettings) {
            $payload['qvbrSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->rateControlMode) {
            if (!Av1RateControlMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "rateControlMode" for "%s". The value "%s" is not a valid "Av1RateControlMode".', __CLASS__, $v));
            }
            $payload['rateControlMode'] = $v;
        }
        if (null !== $v = $this->slices) {
            $payload['slices'] = $v;
        }
        if (null !== $v = $this->spatialAdaptiveQuantization) {
            if (!Av1SpatialAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "spatialAdaptiveQuantization" for "%s". The value "%s" is not a valid "Av1SpatialAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['spatialAdaptiveQuantization'] = $v;
        }

        return $payload;
    }
}
