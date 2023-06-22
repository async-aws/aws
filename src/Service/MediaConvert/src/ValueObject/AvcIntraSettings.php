<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AvcIntraClass;
use AsyncAws\MediaConvert\Enum\AvcIntraFramerateControl;
use AsyncAws\MediaConvert\Enum\AvcIntraFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\AvcIntraInterlaceMode;
use AsyncAws\MediaConvert\Enum\AvcIntraScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\AvcIntraSlowPal;
use AsyncAws\MediaConvert\Enum\AvcIntraTelecine;

/**
 * Required when you choose AVC-Intra for your output video codec. For more information about the AVC-Intra settings,
 * see the relevant specification. For detailed information about SD and HD in AVC-Intra, see
 * https://ieeexplore.ieee.org/document/7290936. For information about 4K/2K in AVC-Intra, see
 * https://pro-av.panasonic.net/en/avc-ultra/AVC-ULTRAoverview.pdf.
 */
final class AvcIntraSettings
{
    /**
     * Specify the AVC-Intra class of your output. The AVC-Intra class selection determines the output video bit rate
     * depending on the frame rate of the output. Outputs with higher class values have higher bitrates and improved image
     * quality. Note that for Class 4K/2K, MediaConvert supports only 4:2:2 chroma subsampling.
     */
    private $avcIntraClass;

    /**
     * Optional when you set AVC-Intra class (avcIntraClass) to Class 4K/2K (CLASS_4K_2K). When you set AVC-Intra class to a
     * different value, this object isn't allowed.
     */
    private $avcIntraUhdSettings;

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
     * Choose the scan line type for the output. Keep the default value, Progressive (PROGRESSIVE) to create a progressive
     * output, regardless of the scan type of your input. Use Top field first (TOP_FIELD) or Bottom field first
     * (BOTTOM_FIELD) to create an output that's interlaced with the same field polarity throughout. Use Follow, default top
     * (FOLLOW_TOP_FIELD) or Follow, default bottom (FOLLOW_BOTTOM_FIELD) to produce outputs with the same field polarity as
     * the source. For jobs that have multiple inputs, the output field polarity might change over the course of the output.
     * Follow behavior depends on the input scan type. If the source is interlaced, the output will be interlaced with the
     * same polarity as the source. If the source is progressive, the output will be interlaced with top field bottom field
     * first, depending on which of the Follow options you choose.
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
     * 25 fps output. When you enable slow PAL, MediaConvert relabels the video frames to 25 fps and resamples your audio to
     * keep it synchronized with the video. Note that enabling this setting will slightly reduce the duration of your video.
     * Required settings: You must also set Framerate to 25. In your JSON job specification, set (framerateControl) to
     * (SPECIFIED), (framerateNumerator) to 25 and (framerateDenominator) to 1.
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
     * @param array{
     *   AvcIntraClass?: null|AvcIntraClass::*,
     *   AvcIntraUhdSettings?: null|AvcIntraUhdSettings|array,
     *   FramerateControl?: null|AvcIntraFramerateControl::*,
     *   FramerateConversionAlgorithm?: null|AvcIntraFramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   InterlaceMode?: null|AvcIntraInterlaceMode::*,
     *   ScanTypeConversionMode?: null|AvcIntraScanTypeConversionMode::*,
     *   SlowPal?: null|AvcIntraSlowPal::*,
     *   Telecine?: null|AvcIntraTelecine::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->avcIntraClass = $input['AvcIntraClass'] ?? null;
        $this->avcIntraUhdSettings = isset($input['AvcIntraUhdSettings']) ? AvcIntraUhdSettings::create($input['AvcIntraUhdSettings']) : null;
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
     *   AvcIntraClass?: null|AvcIntraClass::*,
     *   AvcIntraUhdSettings?: null|AvcIntraUhdSettings|array,
     *   FramerateControl?: null|AvcIntraFramerateControl::*,
     *   FramerateConversionAlgorithm?: null|AvcIntraFramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   InterlaceMode?: null|AvcIntraInterlaceMode::*,
     *   ScanTypeConversionMode?: null|AvcIntraScanTypeConversionMode::*,
     *   SlowPal?: null|AvcIntraSlowPal::*,
     *   Telecine?: null|AvcIntraTelecine::*,
     * }|AvcIntraSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AvcIntraClass::*|null
     */
    public function getAvcIntraClass(): ?string
    {
        return $this->avcIntraClass;
    }

    public function getAvcIntraUhdSettings(): ?AvcIntraUhdSettings
    {
        return $this->avcIntraUhdSettings;
    }

    /**
     * @return AvcIntraFramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return AvcIntraFramerateConversionAlgorithm::*|null
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
     * @return AvcIntraInterlaceMode::*|null
     */
    public function getInterlaceMode(): ?string
    {
        return $this->interlaceMode;
    }

    /**
     * @return AvcIntraScanTypeConversionMode::*|null
     */
    public function getScanTypeConversionMode(): ?string
    {
        return $this->scanTypeConversionMode;
    }

    /**
     * @return AvcIntraSlowPal::*|null
     */
    public function getSlowPal(): ?string
    {
        return $this->slowPal;
    }

    /**
     * @return AvcIntraTelecine::*|null
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
        if (null !== $v = $this->avcIntraClass) {
            if (!AvcIntraClass::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "avcIntraClass" for "%s". The value "%s" is not a valid "AvcIntraClass".', __CLASS__, $v));
            }
            $payload['avcIntraClass'] = $v;
        }
        if (null !== $v = $this->avcIntraUhdSettings) {
            $payload['avcIntraUhdSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->framerateControl) {
            if (!AvcIntraFramerateControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "AvcIntraFramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!AvcIntraFramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "AvcIntraFramerateConversionAlgorithm".', __CLASS__, $v));
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
            if (!AvcIntraInterlaceMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "AvcIntraInterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
        }
        if (null !== $v = $this->scanTypeConversionMode) {
            if (!AvcIntraScanTypeConversionMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "scanTypeConversionMode" for "%s". The value "%s" is not a valid "AvcIntraScanTypeConversionMode".', __CLASS__, $v));
            }
            $payload['scanTypeConversionMode'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!AvcIntraSlowPal::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "AvcIntraSlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!AvcIntraTelecine::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "AvcIntraTelecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }

        return $payload;
    }
}
