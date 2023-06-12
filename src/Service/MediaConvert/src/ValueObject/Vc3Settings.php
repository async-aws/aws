<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Vc3Class;
use AsyncAws\MediaConvert\Enum\Vc3FramerateControl;
use AsyncAws\MediaConvert\Enum\Vc3FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Vc3InterlaceMode;
use AsyncAws\MediaConvert\Enum\Vc3ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\Vc3SlowPal;
use AsyncAws\MediaConvert\Enum\Vc3Telecine;

/**
 * Required when you set (Codec) under (VideoDescription)>(CodecSettings) to the value VC3.
 */
final class Vc3Settings
{
    /**
     * If you are using the console, use the Framerate setting to specify the frame rate for this output. If you want to
     * keep the same frame rate as the input video, choose Follow source. If you want to do frame rate conversion, choose a
     * frame rate from the dropdown list or choose Custom. The framerates shown in the dropdown list are decimal
     * approximations of fractions. If you choose Custom, specify your frame rate as a fraction. If you are creating your
     * transcoding job specification as a JSON file without the console, use FramerateControl to specify which value the
     * service uses for the frame rate for this output. Choose INITIALIZE_FROM_SOURCE if you want the service to use the
     * frame rate from the input. Choose SPECIFIED if you want the service to use the frame rate you specify in the settings
     * FramerateNumerator and FramerateDenominator.
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
     */
    private $framerateConversionAlgorithm;

    /**
     * When you use the API for transcode jobs that use frame rate conversion, specify the frame rate as a fraction. For
     * example, 24000 / 1001 = 23.976 fps. Use FramerateDenominator to specify the denominator of this fraction. In this
     * example, use 1001 for the value of FramerateDenominator. When you use the console for transcode jobs that use frame
     * rate conversion, provide the value as a decimal number for Framerate. In this example, specify 23.976.
     */
    private $framerateDenominator;

    /**
     * When you use the API for transcode jobs that use frame rate conversion, specify the frame rate as a fraction. For
     * example, 24000 / 1001 = 23.976 fps. Use FramerateNumerator to specify the numerator of this fraction. In this
     * example, use 24000 for the value of FramerateNumerator. When you use the console for transcode jobs that use frame
     * rate conversion, provide the value as a decimal number for Framerate. In this example, specify 23.976.
     */
    private $framerateNumerator;

    /**
     * Optional. Choose the scan line type for this output. If you don't specify a value, MediaConvert will create a
     * progressive output.
     */
    private $interlaceMode;

    /**
     * Use this setting for interlaced outputs, when your output frame rate is half of your input frame rate. In this
     * situation, choose Optimized interlacing (INTERLACED_OPTIMIZE) to create a better quality interlaced output. In this
     * case, each progressive frame from the input corresponds to an interlaced field in the output. Keep the default value,
     * Basic interlacing (INTERLACED), for all other output frame rates. With basic interlacing, MediaConvert performs any
     * frame rate conversion first and then interlaces the frames. When you choose Optimized interlacing and you set your
     * output frame rate to a value that isn't suitable for optimized interlacing, MediaConvert automatically falls back to
     * basic interlacing. Required settings: To use optimized interlacing, you must set Telecine (telecine) to None (NONE)
     * or Soft (SOFT). You can't use optimized interlacing for hard telecine outputs. You must also set Interlace mode
     * (interlaceMode) to a value other than Progressive (PROGRESSIVE).
     */
    private $scanTypeConversionMode;

    /**
     * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
     * 25 fps output by relabeling the video frames and resampling your audio. Note that enabling this setting will slightly
     * reduce the duration of your video. Related settings: You must also set Framerate to 25. In your JSON job
     * specification, set (framerateControl) to (SPECIFIED), (framerateNumerator) to 25 and (framerateDenominator) to 1.
     */
    private $slowPal;

    /**
     * When you do frame rate conversion from 23.976 frames per second (fps) to 29.97 fps, and your output scan type is
     * interlaced, you can optionally enable hard telecine (HARD) to create a smoother picture. When you keep the default
     * value, None (NONE), MediaConvert does a standard frame rate conversion to 29.97 without doing anything with the field
     * polarity to create a smoother picture.
     */
    private $telecine;

    /**
     * Specify the VC3 class to choose the quality characteristics for this output. VC3 class, together with the settings
     * Framerate (framerateNumerator and framerateDenominator) and Resolution (height and width), determine your output
     * bitrate. For example, say that your video resolution is 1920x1080 and your framerate is 29.97. Then Class 145
     * (CLASS_145) gives you an output with a bitrate of approximately 145 Mbps and Class 220 (CLASS_220) gives you and
     * output with a bitrate of approximately 220 Mbps. VC3 class also specifies the color bit depth of your output.
     */
    private $vc3Class;

    /**
     * @param array{
     *   FramerateControl?: null|Vc3FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|Vc3FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   InterlaceMode?: null|Vc3InterlaceMode::*,
     *   ScanTypeConversionMode?: null|Vc3ScanTypeConversionMode::*,
     *   SlowPal?: null|Vc3SlowPal::*,
     *   Telecine?: null|Vc3Telecine::*,
     *   Vc3Class?: null|Vc3Class::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->framerateControl = $input['FramerateControl'] ?? null;
        $this->framerateConversionAlgorithm = $input['FramerateConversionAlgorithm'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->interlaceMode = $input['InterlaceMode'] ?? null;
        $this->scanTypeConversionMode = $input['ScanTypeConversionMode'] ?? null;
        $this->slowPal = $input['SlowPal'] ?? null;
        $this->telecine = $input['Telecine'] ?? null;
        $this->vc3Class = $input['Vc3Class'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Vc3FramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return Vc3FramerateConversionAlgorithm::*|null
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
     * @return Vc3InterlaceMode::*|null
     */
    public function getInterlaceMode(): ?string
    {
        return $this->interlaceMode;
    }

    /**
     * @return Vc3ScanTypeConversionMode::*|null
     */
    public function getScanTypeConversionMode(): ?string
    {
        return $this->scanTypeConversionMode;
    }

    /**
     * @return Vc3SlowPal::*|null
     */
    public function getSlowPal(): ?string
    {
        return $this->slowPal;
    }

    /**
     * @return Vc3Telecine::*|null
     */
    public function getTelecine(): ?string
    {
        return $this->telecine;
    }

    /**
     * @return Vc3Class::*|null
     */
    public function getVc3Class(): ?string
    {
        return $this->vc3Class;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->framerateControl) {
            if (!Vc3FramerateControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "Vc3FramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!Vc3FramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "Vc3FramerateConversionAlgorithm".', __CLASS__, $v));
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
            if (!Vc3InterlaceMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "Vc3InterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
        }
        if (null !== $v = $this->scanTypeConversionMode) {
            if (!Vc3ScanTypeConversionMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "scanTypeConversionMode" for "%s". The value "%s" is not a valid "Vc3ScanTypeConversionMode".', __CLASS__, $v));
            }
            $payload['scanTypeConversionMode'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!Vc3SlowPal::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "Vc3SlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!Vc3Telecine::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "Vc3Telecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }
        if (null !== $v = $this->vc3Class) {
            if (!Vc3Class::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "vc3Class" for "%s". The value "%s" is not a valid "Vc3Class".', __CLASS__, $v));
            }
            $payload['vc3Class'] = $v;
        }

        return $payload;
    }
}
