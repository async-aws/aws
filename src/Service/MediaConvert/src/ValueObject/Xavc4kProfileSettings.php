<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Xavc4kProfileBitrateClass;
use AsyncAws\MediaConvert\Enum\Xavc4kProfileCodecProfile;
use AsyncAws\MediaConvert\Enum\Xavc4kProfileQualityTuningLevel;
use AsyncAws\MediaConvert\Enum\XavcFlickerAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\XavcGopBReference;

/**
 * Required when you set Profile to the value XAVC_4K.
 */
final class Xavc4kProfileSettings
{
    /**
     * Specify the XAVC 4k (Long GOP) Bitrate Class to set the bitrate of your output. Outputs of the same class have
     * similar image quality over the operating points that are valid for that class.
     *
     * @var Xavc4kProfileBitrateClass::*|null
     */
    private $bitrateClass;

    /**
     * Specify the codec profile for this output. Choose High, 8-bit, 4:2:0 (HIGH) or High, 10-bit, 4:2:2 (HIGH_422). These
     * profiles are specified in ITU-T H.264.
     *
     * @var Xavc4kProfileCodecProfile::*|null
     */
    private $codecProfile;

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
    private $gopBReference;

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
     * Optional. Use Quality tuning level to choose how you want to trade off encoding speed for output video quality. The
     * default behavior is faster, lower quality, single-pass encoding.
     *
     * @var Xavc4kProfileQualityTuningLevel::*|null
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
     * @param array{
     *   BitrateClass?: Xavc4kProfileBitrateClass::*|null,
     *   CodecProfile?: Xavc4kProfileCodecProfile::*|null,
     *   FlickerAdaptiveQuantization?: XavcFlickerAdaptiveQuantization::*|null,
     *   GopBReference?: XavcGopBReference::*|null,
     *   GopClosedCadence?: int|null,
     *   HrdBufferSize?: int|null,
     *   QualityTuningLevel?: Xavc4kProfileQualityTuningLevel::*|null,
     *   Slices?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitrateClass = $input['BitrateClass'] ?? null;
        $this->codecProfile = $input['CodecProfile'] ?? null;
        $this->flickerAdaptiveQuantization = $input['FlickerAdaptiveQuantization'] ?? null;
        $this->gopBReference = $input['GopBReference'] ?? null;
        $this->gopClosedCadence = $input['GopClosedCadence'] ?? null;
        $this->hrdBufferSize = $input['HrdBufferSize'] ?? null;
        $this->qualityTuningLevel = $input['QualityTuningLevel'] ?? null;
        $this->slices = $input['Slices'] ?? null;
    }

    /**
     * @param array{
     *   BitrateClass?: Xavc4kProfileBitrateClass::*|null,
     *   CodecProfile?: Xavc4kProfileCodecProfile::*|null,
     *   FlickerAdaptiveQuantization?: XavcFlickerAdaptiveQuantization::*|null,
     *   GopBReference?: XavcGopBReference::*|null,
     *   GopClosedCadence?: int|null,
     *   HrdBufferSize?: int|null,
     *   QualityTuningLevel?: Xavc4kProfileQualityTuningLevel::*|null,
     *   Slices?: int|null,
     * }|Xavc4kProfileSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Xavc4kProfileBitrateClass::*|null
     */
    public function getBitrateClass(): ?string
    {
        return $this->bitrateClass;
    }

    /**
     * @return Xavc4kProfileCodecProfile::*|null
     */
    public function getCodecProfile(): ?string
    {
        return $this->codecProfile;
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
    public function getGopBReference(): ?string
    {
        return $this->gopBReference;
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
     * @return Xavc4kProfileQualityTuningLevel::*|null
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
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->bitrateClass) {
            if (!Xavc4kProfileBitrateClass::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "bitrateClass" for "%s". The value "%s" is not a valid "Xavc4kProfileBitrateClass".', __CLASS__, $v));
            }
            $payload['bitrateClass'] = $v;
        }
        if (null !== $v = $this->codecProfile) {
            if (!Xavc4kProfileCodecProfile::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "codecProfile" for "%s". The value "%s" is not a valid "Xavc4kProfileCodecProfile".', __CLASS__, $v));
            }
            $payload['codecProfile'] = $v;
        }
        if (null !== $v = $this->flickerAdaptiveQuantization) {
            if (!XavcFlickerAdaptiveQuantization::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "flickerAdaptiveQuantization" for "%s". The value "%s" is not a valid "XavcFlickerAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['flickerAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->gopBReference) {
            if (!XavcGopBReference::exists($v)) {
                /** @psalm-suppress NoValue */
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
        if (null !== $v = $this->qualityTuningLevel) {
            if (!Xavc4kProfileQualityTuningLevel::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "qualityTuningLevel" for "%s". The value "%s" is not a valid "Xavc4kProfileQualityTuningLevel".', __CLASS__, $v));
            }
            $payload['qualityTuningLevel'] = $v;
        }
        if (null !== $v = $this->slices) {
            $payload['slices'] = $v;
        }

        return $payload;
    }
}
