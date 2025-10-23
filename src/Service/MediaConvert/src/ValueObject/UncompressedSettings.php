<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\UncompressedFourcc;
use AsyncAws\MediaConvert\Enum\UncompressedFramerateControl;
use AsyncAws\MediaConvert\Enum\UncompressedFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\UncompressedInterlaceMode;
use AsyncAws\MediaConvert\Enum\UncompressedScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\UncompressedSlowPal;
use AsyncAws\MediaConvert\Enum\UncompressedTelecine;

/**
 * Required when you set Codec, under VideoDescription>CodecSettings to the value UNCOMPRESSED.
 */
final class UncompressedSettings
{
    /**
     * The four character code for the uncompressed video.
     *
     * @var UncompressedFourcc::*|null
     */
    private $fourcc;

    /**
     * Use the Framerate setting to specify the frame rate for this output. If you want to keep the same frame rate as the
     * input video, choose Follow source. If you want to do frame rate conversion, choose a frame rate from the dropdown
     * list or choose Custom. The framerates shown in the dropdown list are decimal approximations of fractions. If you
     * choose Custom, specify your frame rate as a fraction.
     *
     * @var UncompressedFramerateControl::*|null
     */
    private $framerateControl;

    /**
     * Choose the method that you want MediaConvert to use when increasing or decreasing your video's frame rate. For
     * numerically simple conversions, such as 60 fps to 30 fps: We recommend that you keep the default value, Drop
     * duplicate. For numerically complex conversions, to avoid stutter: Choose Interpolate. This results in a smooth
     * picture, but might introduce undesirable video artifacts. For complex frame rate conversions, especially if your
     * source video has already been converted from its original cadence: Choose FrameFormer to do motion-compensated
     * interpolation. FrameFormer uses the best conversion method frame by frame. Note that using FrameFormer increases the
     * transcoding time and incurs a significant add-on cost. When you choose FrameFormer, your input video resolution must
     * be at least 128x96. To create an output with the same number of frames as your input: Choose Maintain frame count.
     * When you do, MediaConvert will not drop, interpolate, add, or otherwise change the frame count from your input to
     * your output. Note that since the frame count is maintained, the duration of your output will become shorter at higher
     * frame rates and longer at lower frame rates.
     *
     * @var UncompressedFramerateConversionAlgorithm::*|null
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
     * Optional. Choose the scan line type for this output. If you don't specify a value, MediaConvert will create a
     * progressive output.
     *
     * @var UncompressedInterlaceMode::*|null
     */
    private $interlaceMode;

    /**
     * Use this setting for interlaced outputs, when your output frame rate is half of your input frame rate. In this
     * situation, choose Optimized interlacing to create a better quality interlaced output. In this case, each progressive
     * frame from the input corresponds to an interlaced field in the output. Keep the default value, Basic interlacing, for
     * all other output frame rates. With basic interlacing, MediaConvert performs any frame rate conversion first and then
     * interlaces the frames. When you choose Optimized interlacing and you set your output frame rate to a value that isn't
     * suitable for optimized interlacing, MediaConvert automatically falls back to basic interlacing. Required settings: To
     * use optimized interlacing, you must set Telecine to None or Soft. You can't use optimized interlacing for hard
     * telecine outputs. You must also set Interlace mode to a value other than Progressive.
     *
     * @var UncompressedScanTypeConversionMode::*|null
     */
    private $scanTypeConversionMode;

    /**
     * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
     * 25 fps output by relabeling the video frames and resampling your audio. Note that enabling this setting will slightly
     * reduce the duration of your video. Related settings: You must also set Framerate to 25.
     *
     * @var UncompressedSlowPal::*|null
     */
    private $slowPal;

    /**
     * When you do frame rate conversion from 23.976 frames per second (fps) to 29.97 fps, and your output scan type is
     * interlaced, you can optionally enable hard telecine to create a smoother picture. When you keep the default value,
     * None, MediaConvert does a standard frame rate conversion to 29.97 without doing anything with the field polarity to
     * create a smoother picture.
     *
     * @var UncompressedTelecine::*|null
     */
    private $telecine;

    /**
     * @param array{
     *   Fourcc?: UncompressedFourcc::*|null,
     *   FramerateControl?: UncompressedFramerateControl::*|null,
     *   FramerateConversionAlgorithm?: UncompressedFramerateConversionAlgorithm::*|null,
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     *   InterlaceMode?: UncompressedInterlaceMode::*|null,
     *   ScanTypeConversionMode?: UncompressedScanTypeConversionMode::*|null,
     *   SlowPal?: UncompressedSlowPal::*|null,
     *   Telecine?: UncompressedTelecine::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fourcc = $input['Fourcc'] ?? null;
        $this->framerateControl = $input['FramerateControl'] ?? null;
        $this->framerateConversionAlgorithm = $input['FramerateConversionAlgorithm'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->interlaceMode = $input['InterlaceMode'] ?? null;
        $this->scanTypeConversionMode = $input['ScanTypeConversionMode'] ?? null;
        $this->slowPal = $input['SlowPal'] ?? null;
        $this->telecine = $input['Telecine'] ?? null;
    }

    /**
     * @param array{
     *   Fourcc?: UncompressedFourcc::*|null,
     *   FramerateControl?: UncompressedFramerateControl::*|null,
     *   FramerateConversionAlgorithm?: UncompressedFramerateConversionAlgorithm::*|null,
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     *   InterlaceMode?: UncompressedInterlaceMode::*|null,
     *   ScanTypeConversionMode?: UncompressedScanTypeConversionMode::*|null,
     *   SlowPal?: UncompressedSlowPal::*|null,
     *   Telecine?: UncompressedTelecine::*|null,
     * }|UncompressedSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return UncompressedFourcc::*|null
     */
    public function getFourcc(): ?string
    {
        return $this->fourcc;
    }

    /**
     * @return UncompressedFramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return UncompressedFramerateConversionAlgorithm::*|null
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

    /**
     * @return UncompressedInterlaceMode::*|null
     */
    public function getInterlaceMode(): ?string
    {
        return $this->interlaceMode;
    }

    /**
     * @return UncompressedScanTypeConversionMode::*|null
     */
    public function getScanTypeConversionMode(): ?string
    {
        return $this->scanTypeConversionMode;
    }

    /**
     * @return UncompressedSlowPal::*|null
     */
    public function getSlowPal(): ?string
    {
        return $this->slowPal;
    }

    /**
     * @return UncompressedTelecine::*|null
     */
    public function getTelecine(): ?string
    {
        return $this->telecine;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->fourcc) {
            if (!UncompressedFourcc::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "fourcc" for "%s". The value "%s" is not a valid "UncompressedFourcc".', __CLASS__, $v));
            }
            $payload['fourcc'] = $v;
        }
        if (null !== $v = $this->framerateControl) {
            if (!UncompressedFramerateControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "UncompressedFramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!UncompressedFramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "UncompressedFramerateConversionAlgorithm".', __CLASS__, $v));
            }
            $payload['framerateConversionAlgorithm'] = $v;
        }
        if (null !== $v = $this->framerateDenominator) {
            $payload['framerateDenominator'] = $v;
        }
        if (null !== $v = $this->framerateNumerator) {
            $payload['framerateNumerator'] = $v;
        }
        if (null !== $v = $this->interlaceMode) {
            if (!UncompressedInterlaceMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "UncompressedInterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
        }
        if (null !== $v = $this->scanTypeConversionMode) {
            if (!UncompressedScanTypeConversionMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scanTypeConversionMode" for "%s". The value "%s" is not a valid "UncompressedScanTypeConversionMode".', __CLASS__, $v));
            }
            $payload['scanTypeConversionMode'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!UncompressedSlowPal::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "UncompressedSlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!UncompressedTelecine::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "UncompressedTelecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }

        return $payload;
    }
}
