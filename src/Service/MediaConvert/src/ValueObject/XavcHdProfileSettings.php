<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\XavcFlickerAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\XavcGopBReference;
use AsyncAws\MediaConvert\Enum\XavcHdProfileBitrateClass;
use AsyncAws\MediaConvert\Enum\XavcHdProfileQualityTuningLevel;
use AsyncAws\MediaConvert\Enum\XavcHdProfileTelecine;
use AsyncAws\MediaConvert\Enum\XavcInterlaceMode;

/**
 * Required when you set Profile to the value XAVC_HD.
 */
final class XavcHdProfileSettings
{
    /**
     * Specify the XAVC HD (Long GOP) Bitrate Class to set the bitrate of your output. Outputs of the same class have
     * similar image quality over the operating points that are valid for that class.
     *
     * @var XavcHdProfileBitrateClass::*|null
     */
    private $bitrateClass;

    /**
     * The best way to set up adaptive quantization is to keep the default value, Auto, for the setting Adaptive
     * quantization. When you do so, MediaConvert automatically applies the best types of quantization for your video
     * content. Include this setting in your JSON job specification only when you choose to change the default value for
     * Adaptive quantization. Enable this setting to have the encoder reduce I-frame pop. I-frame pop appears as a visual
     * flicker that can arise when the encoder saves bits by copying some macroblocks many times from frame to frame, and
     * then refreshes them at the I-frame. When you enable this setting, the encoder updates these macroblocks slightly more
     * often to smooth out the flicker. This setting is disabled by default. Related setting: In addition to enabling this
     * setting, you must also set Adaptive quantization to a value other than Off or Auto. Use Adaptive quantization to
     * adjust the degree of smoothing that Flicker adaptive quantization provides.
     *
     * @var XavcFlickerAdaptiveQuantization::*|null
     */
    private $flickerAdaptiveQuantization;

    /**
     * Specify whether the encoder uses B-frames as reference frames for other pictures in the same GOP. Choose Allow to
     * allow the encoder to use B-frames as reference frames. Choose Don't allow to prevent the encoder from using B-frames
     * as reference frames.
     *
     * @var XavcGopBReference::*|null
     */
    private $gopBreference;

    /**
     * Frequency of closed GOPs. In streaming applications, it is recommended that this be set to 1 so a decoder joining
     * mid-stream will receive an IDR frame as quickly as possible. Setting this value to 0 will break output segmenting.
     *
     * @var int|null
     */
    private $gopClosedCadence;

    /**
     * Specify the size of the buffer that MediaConvert uses in the HRD buffer model for this output. Specify this value in
     * bits; for example, enter five megabits as 5000000. When you don't set this value, or you set it to zero, MediaConvert
     * calculates the default by doubling the bitrate of this output point.
     *
     * @var int|null
     */
    private $hrdBufferSize;

    /**
     * Choose the scan line type for the output. Keep the default value, Progressive to create a progressive output,
     * regardless of the scan type of your input. Use Top field first or Bottom field first to create an output that's
     * interlaced with the same field polarity throughout. Use Follow, default top or Follow, default bottom to produce
     * outputs with the same field polarity as the source. For jobs that have multiple inputs, the output field polarity
     * might change over the course of the output. Follow behavior depends on the input scan type. If the source is
     * interlaced, the output will be interlaced with the same polarity as the source. If the source is progressive, the
     * output will be interlaced with top field bottom field first, depending on which of the Follow options you choose.
     *
     * @var XavcInterlaceMode::*|null
     */
    private $interlaceMode;

    /**
     * Optional. Use Quality tuning level to choose how you want to trade off encoding speed for output video quality. The
     * default behavior is faster, lower quality, single-pass encoding.
     *
     * @var XavcHdProfileQualityTuningLevel::*|null
     */
    private $qualityTuningLevel;

    /**
     * Number of slices per picture. Must be less than or equal to the number of macroblock rows for progressive pictures,
     * and less than or equal to half the number of macroblock rows for interlaced pictures.
     *
     * @var int|null
     */
    private $slices;

    /**
     * Ignore this setting unless you set Frame rate (framerateNumerator divided by framerateDenominator) to 29.970. If your
     * input framerate is 23.976, choose Hard. Otherwise, keep the default value None. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/working-with-telecine-and-inverse-telecine.html.
     *
     * @var XavcHdProfileTelecine::*|null
     */
    private $telecine;

    /**
     * @param array{
     *   BitrateClass?: XavcHdProfileBitrateClass::*|null,
     *   FlickerAdaptiveQuantization?: XavcFlickerAdaptiveQuantization::*|null,
     *   GopBReference?: XavcGopBReference::*|null,
     *   GopClosedCadence?: int|null,
     *   HrdBufferSize?: int|null,
     *   InterlaceMode?: XavcInterlaceMode::*|null,
     *   QualityTuningLevel?: XavcHdProfileQualityTuningLevel::*|null,
     *   Slices?: int|null,
     *   Telecine?: XavcHdProfileTelecine::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitrateClass = $input['BitrateClass'] ?? null;
        $this->flickerAdaptiveQuantization = $input['FlickerAdaptiveQuantization'] ?? null;
        $this->gopBreference = $input['GopBReference'] ?? null;
        $this->gopClosedCadence = $input['GopClosedCadence'] ?? null;
        $this->hrdBufferSize = $input['HrdBufferSize'] ?? null;
        $this->interlaceMode = $input['InterlaceMode'] ?? null;
        $this->qualityTuningLevel = $input['QualityTuningLevel'] ?? null;
        $this->slices = $input['Slices'] ?? null;
        $this->telecine = $input['Telecine'] ?? null;
    }

    /**
     * @param array{
     *   BitrateClass?: XavcHdProfileBitrateClass::*|null,
     *   FlickerAdaptiveQuantization?: XavcFlickerAdaptiveQuantization::*|null,
     *   GopBReference?: XavcGopBReference::*|null,
     *   GopClosedCadence?: int|null,
     *   HrdBufferSize?: int|null,
     *   InterlaceMode?: XavcInterlaceMode::*|null,
     *   QualityTuningLevel?: XavcHdProfileQualityTuningLevel::*|null,
     *   Slices?: int|null,
     *   Telecine?: XavcHdProfileTelecine::*|null,
     * }|XavcHdProfileSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return XavcHdProfileBitrateClass::*|null
     */
    public function getBitrateClass(): ?string
    {
        return $this->bitrateClass;
    }

    /**
     * @return XavcFlickerAdaptiveQuantization::*|null
     */
    public function getFlickerAdaptiveQuantization(): ?string
    {
        return $this->flickerAdaptiveQuantization;
    }

    /**
     * @return XavcGopBReference::*|null
     */
    public function getGopBreference(): ?string
    {
        return $this->gopBreference;
    }

    public function getGopClosedCadence(): ?int
    {
        return $this->gopClosedCadence;
    }

    public function getHrdBufferSize(): ?int
    {
        return $this->hrdBufferSize;
    }

    /**
     * @return XavcInterlaceMode::*|null
     */
    public function getInterlaceMode(): ?string
    {
        return $this->interlaceMode;
    }

    /**
     * @return XavcHdProfileQualityTuningLevel::*|null
     */
    public function getQualityTuningLevel(): ?string
    {
        return $this->qualityTuningLevel;
    }

    public function getSlices(): ?int
    {
        return $this->slices;
    }

    /**
     * @return XavcHdProfileTelecine::*|null
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
        if (null !== $v = $this->bitrateClass) {
            if (!XavcHdProfileBitrateClass::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "bitrateClass" for "%s". The value "%s" is not a valid "XavcHdProfileBitrateClass".', __CLASS__, $v));
            }
            $payload['bitrateClass'] = $v;
        }
        if (null !== $v = $this->flickerAdaptiveQuantization) {
            if (!XavcFlickerAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "flickerAdaptiveQuantization" for "%s". The value "%s" is not a valid "XavcFlickerAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['flickerAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->gopBreference) {
            if (!XavcGopBReference::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "gopBReference" for "%s". The value "%s" is not a valid "XavcGopBReference".', __CLASS__, $v));
            }
            $payload['gopBReference'] = $v;
        }
        if (null !== $v = $this->gopClosedCadence) {
            $payload['gopClosedCadence'] = $v;
        }
        if (null !== $v = $this->hrdBufferSize) {
            $payload['hrdBufferSize'] = $v;
        }
        if (null !== $v = $this->interlaceMode) {
            if (!XavcInterlaceMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "XavcInterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
        }
        if (null !== $v = $this->qualityTuningLevel) {
            if (!XavcHdProfileQualityTuningLevel::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "qualityTuningLevel" for "%s". The value "%s" is not a valid "XavcHdProfileQualityTuningLevel".', __CLASS__, $v));
            }
            $payload['qualityTuningLevel'] = $v;
        }
        if (null !== $v = $this->slices) {
            $payload['slices'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!XavcHdProfileTelecine::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "XavcHdProfileTelecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }

        return $payload;
    }
}
