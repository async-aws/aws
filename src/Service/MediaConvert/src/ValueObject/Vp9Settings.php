<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Vp9FramerateControl;
use AsyncAws\MediaConvert\Enum\Vp9FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Vp9ParControl;
use AsyncAws\MediaConvert\Enum\Vp9QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\Vp9RateControlMode;

/**
 * Required when you set Codec to the value VP9.
 */
final class Vp9Settings
{
    /**
     * Target bitrate in bits/second. For example, enter five megabits per second as 5000000.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * If you are using the console, use the Framerate setting to specify the frame rate for this output. If you want to
     * keep the same frame rate as the input video, choose Follow source. If you want to do frame rate conversion, choose a
     * frame rate from the dropdown list or choose Custom. The framerates shown in the dropdown list are decimal
     * approximations of fractions. If you choose Custom, specify your frame rate as a fraction.
     *
     * @var Vp9FramerateControl::*|null
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
     * @var Vp9FramerateConversionAlgorithm::*|null
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
     * GOP Length (keyframe interval) in frames. Must be greater than zero.
     *
     * @var float|null
     */
    private $gopSize;

    /**
     * Size of buffer (HRD buffer model) in bits. For example, enter five megabits as 5000000.
     *
     * @var int|null
     */
    private $hrdBufferSize;

    /**
     * Ignore this setting unless you set qualityTuningLevel to MULTI_PASS. Optional. Specify the maximum bitrate in
     * bits/second. For example, enter five megabits per second as 5000000. The default behavior uses twice the target
     * bitrate as the maximum bitrate.
     *
     * @var int|null
     */
    private $maxBitrate;

    /**
     * Optional. Specify how the service determines the pixel aspect ratio for this output. The default behavior is to use
     * the same pixel aspect ratio as your input video.
     *
     * @var Vp9ParControl::*|null
     */
    private $parControl;

    /**
     * Required when you set Pixel aspect ratio to SPECIFIED. On the console, this corresponds to any value other than
     * Follow source. When you specify an output pixel aspect ratio (PAR) that is different from your input video PAR,
     * provide your output PAR as a ratio. For example, for D1/DV NTSC widescreen, you would specify the ratio 40:33. In
     * this example, the value for parDenominator is 33.
     *
     * @var int|null
     */
    private $parDenominator;

    /**
     * Required when you set Pixel aspect ratio to SPECIFIED. On the console, this corresponds to any value other than
     * Follow source. When you specify an output pixel aspect ratio (PAR) that is different from your input video PAR,
     * provide your output PAR as a ratio. For example, for D1/DV NTSC widescreen, you would specify the ratio 40:33. In
     * this example, the value for parNumerator is 40.
     *
     * @var int|null
     */
    private $parNumerator;

    /**
     * Optional. Use Quality tuning level to choose how you want to trade off encoding speed for output video quality. The
     * default behavior is faster, lower quality, multi-pass encoding.
     *
     * @var Vp9QualityTuningLevel::*|null
     */
    private $qualityTuningLevel;

    /**
     * With the VP9 codec, you can use only the variable bitrate (VBR) rate control mode.
     *
     * @var Vp9RateControlMode::*|null
     */
    private $rateControlMode;

    /**
     * @param array{
     *   Bitrate?: null|int,
     *   FramerateControl?: null|Vp9FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|Vp9FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopSize?: null|float,
     *   HrdBufferSize?: null|int,
     *   MaxBitrate?: null|int,
     *   ParControl?: null|Vp9ParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   QualityTuningLevel?: null|Vp9QualityTuningLevel::*,
     *   RateControlMode?: null|Vp9RateControlMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->framerateControl = $input['FramerateControl'] ?? null;
        $this->framerateConversionAlgorithm = $input['FramerateConversionAlgorithm'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->gopSize = $input['GopSize'] ?? null;
        $this->hrdBufferSize = $input['HrdBufferSize'] ?? null;
        $this->maxBitrate = $input['MaxBitrate'] ?? null;
        $this->parControl = $input['ParControl'] ?? null;
        $this->parDenominator = $input['ParDenominator'] ?? null;
        $this->parNumerator = $input['ParNumerator'] ?? null;
        $this->qualityTuningLevel = $input['QualityTuningLevel'] ?? null;
        $this->rateControlMode = $input['RateControlMode'] ?? null;
    }

    /**
     * @param array{
     *   Bitrate?: null|int,
     *   FramerateControl?: null|Vp9FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|Vp9FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopSize?: null|float,
     *   HrdBufferSize?: null|int,
     *   MaxBitrate?: null|int,
     *   ParControl?: null|Vp9ParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   QualityTuningLevel?: null|Vp9QualityTuningLevel::*,
     *   RateControlMode?: null|Vp9RateControlMode::*,
     * }|Vp9Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * @return Vp9FramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return Vp9FramerateConversionAlgorithm::*|null
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

    public function getHrdBufferSize(): ?int
    {
        return $this->hrdBufferSize;
    }

    public function getMaxBitrate(): ?int
    {
        return $this->maxBitrate;
    }

    /**
     * @return Vp9ParControl::*|null
     */
    public function getParControl(): ?string
    {
        return $this->parControl;
    }

    public function getParDenominator(): ?int
    {
        return $this->parDenominator;
    }

    public function getParNumerator(): ?int
    {
        return $this->parNumerator;
    }

    /**
     * @return Vp9QualityTuningLevel::*|null
     */
    public function getQualityTuningLevel(): ?string
    {
        return $this->qualityTuningLevel;
    }

    /**
     * @return Vp9RateControlMode::*|null
     */
    public function getRateControlMode(): ?string
    {
        return $this->rateControlMode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->framerateControl) {
            if (!Vp9FramerateControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "Vp9FramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!Vp9FramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "Vp9FramerateConversionAlgorithm".', __CLASS__, $v));
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
        if (null !== $v = $this->hrdBufferSize) {
            $payload['hrdBufferSize'] = $v;
        }
        if (null !== $v = $this->maxBitrate) {
            $payload['maxBitrate'] = $v;
        }
        if (null !== $v = $this->parControl) {
            if (!Vp9ParControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "parControl" for "%s". The value "%s" is not a valid "Vp9ParControl".', __CLASS__, $v));
            }
            $payload['parControl'] = $v;
        }
        if (null !== $v = $this->parDenominator) {
            $payload['parDenominator'] = $v;
        }
        if (null !== $v = $this->parNumerator) {
            $payload['parNumerator'] = $v;
        }
        if (null !== $v = $this->qualityTuningLevel) {
            if (!Vp9QualityTuningLevel::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "qualityTuningLevel" for "%s". The value "%s" is not a valid "Vp9QualityTuningLevel".', __CLASS__, $v));
            }
            $payload['qualityTuningLevel'] = $v;
        }
        if (null !== $v = $this->rateControlMode) {
            if (!Vp9RateControlMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "rateControlMode" for "%s". The value "%s" is not a valid "Vp9RateControlMode".', __CLASS__, $v));
            }
            $payload['rateControlMode'] = $v;
        }

        return $payload;
    }
}
