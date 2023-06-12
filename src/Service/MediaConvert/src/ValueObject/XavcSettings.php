<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\XavcAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\XavcEntropyEncoding;
use AsyncAws\MediaConvert\Enum\XavcFramerateControl;
use AsyncAws\MediaConvert\Enum\XavcFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\XavcProfile;
use AsyncAws\MediaConvert\Enum\XavcSlowPal;
use AsyncAws\MediaConvert\Enum\XavcSpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\XavcTemporalAdaptiveQuantization;

/**
 * Required when you set (Codec) under (VideoDescription)>(CodecSettings) to the value XAVC.
 */
final class XavcSettings
{
    /**
     * Keep the default value, Auto (AUTO), for this setting to have MediaConvert automatically apply the best types of
     * quantization for your video content. When you want to apply your quantization settings manually, you must set
     * Adaptive quantization (adaptiveQuantization) to a value other than Auto (AUTO). Use this setting to specify the
     * strength of any adaptive quantization filters that you enable. If you don't want MediaConvert to do any adaptive
     * quantization in this transcode, set Adaptive quantization to Off (OFF). Related settings: The value that you choose
     * here applies to the following settings: Flicker adaptive quantization (flickerAdaptiveQuantization), Spatial adaptive
     * quantization (spatialAdaptiveQuantization), and Temporal adaptive quantization (temporalAdaptiveQuantization).
     */
    private $adaptiveQuantization;

    /**
     * Optional. Choose a specific entropy encoding mode only when you want to override XAVC recommendations. If you choose
     * the value auto, MediaConvert uses the mode that the XAVC file format specifies given this output's operating point.
     */
    private $entropyEncoding;

    /**
     * If you are using the console, use the Frame rate setting to specify the frame rate for this output. If you want to
     * keep the same frame rate as the input video, choose Follow source. If you want to do frame rate conversion, choose a
     * frame rate from the dropdown list. The framerates shown in the dropdown list are decimal approximations of fractions.
     * If you are creating your transcoding job specification as a JSON file without the console, use FramerateControl to
     * specify which value the service uses for the frame rate for this output. Choose INITIALIZE_FROM_SOURCE if you want
     * the service to use the frame rate from the input. Choose SPECIFIED if you want the service to use the frame rate that
     * you specify in the settings FramerateNumerator and FramerateDenominator.
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
     * rate conversion, provide the value as a decimal number for Frame rate. In this example, specify 23.976.
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
     * Specify the XAVC profile for this output. For more information, see the Sony documentation at
     * https://www.xavc-info.org/. Note that MediaConvert doesn't support the interlaced video XAVC operating points for
     * XAVC_HD_INTRA_CBG. To create an interlaced XAVC output, choose the profile XAVC_HD.
     */
    private $profile;

    /**
     * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
     * 25 fps output by relabeling the video frames and resampling your audio. Note that enabling this setting will slightly
     * reduce the duration of your video. Related settings: You must also set Frame rate to 25. In your JSON job
     * specification, set (framerateControl) to (SPECIFIED), (framerateNumerator) to 25 and (framerateDenominator) to 1.
     */
    private $slowPal;

    /**
     * Ignore this setting unless your downstream workflow requires that you specify it explicitly. Otherwise, we recommend
     * that you adjust the softness of your output by using a lower value for the setting Sharpness (sharpness) or by
     * enabling a noise reducer filter (noiseReducerFilter). The Softness (softness) setting specifies the quantization
     * matrices that the encoder uses. Keep the default value, 0, for flat quantization. Choose the value 1 or 16 to use the
     * default JVT softening quantization matricies from the H.264 specification. Choose a value from 17 to 128 to use
     * planar interpolation. Increasing values from 17 to 128 result in increasing reduction of high-frequency data. The
     * value 128 results in the softest video.
     */
    private $softness;

    /**
     * The best way to set up adaptive quantization is to keep the default value, Auto (AUTO), for the setting Adaptive
     * quantization (adaptiveQuantization). When you do so, MediaConvert automatically applies the best types of
     * quantization for your video content. Include this setting in your JSON job specification only when you choose to
     * change the default value for Adaptive quantization. For this setting, keep the default value, Enabled (ENABLED), to
     * adjust quantization within each frame based on spatial variation of content complexity. When you enable this feature,
     * the encoder uses fewer bits on areas that can sustain more distortion with no noticeable visual degradation and uses
     * more bits on areas where any small distortion will be noticeable. For example, complex textured blocks are encoded
     * with fewer bits and smooth textured blocks are encoded with more bits. Enabling this feature will almost always
     * improve your video quality. Note, though, that this feature doesn't take into account where the viewer's attention is
     * likely to be. If viewers are likely to be focusing their attention on a part of the screen with a lot of complex
     * texture, you might choose to disable this feature. Related setting: When you enable spatial adaptive quantization,
     * set the value for Adaptive quantization (adaptiveQuantization) depending on your content. For homogeneous content,
     * such as cartoons and video games, set it to Low. For content with a wider variety of textures, set it to High or
     * Higher.
     */
    private $spatialAdaptiveQuantization;

    /**
     * The best way to set up adaptive quantization is to keep the default value, Auto (AUTO), for the setting Adaptive
     * quantization (adaptiveQuantization). When you do so, MediaConvert automatically applies the best types of
     * quantization for your video content. Include this setting in your JSON job specification only when you choose to
     * change the default value for Adaptive quantization. For this setting, keep the default value, Enabled (ENABLED), to
     * adjust quantization within each frame based on temporal variation of content complexity. When you enable this
     * feature, the encoder uses fewer bits on areas of the frame that aren't moving and uses more bits on complex objects
     * with sharp edges that move a lot. For example, this feature improves the readability of text tickers on newscasts and
     * scoreboards on sports matches. Enabling this feature will almost always improve your video quality. Note, though,
     * that this feature doesn't take into account where the viewer's attention is likely to be. If viewers are likely to be
     * focusing their attention on a part of the screen that doesn't have moving objects with sharp edges, such as sports
     * athletes' faces, you might choose to disable this feature. Related setting: When you enable temporal adaptive
     * quantization, adjust the strength of the filter with the setting Adaptive quantization (adaptiveQuantization).
     */
    private $temporalAdaptiveQuantization;

    /**
     * Required when you set (Profile) under (VideoDescription)>(CodecSettings)>(XavcSettings) to the value
     * XAVC_4K_INTRA_CBG.
     */
    private $xavc4kIntraCbgProfileSettings;

    /**
     * Required when you set (Profile) under (VideoDescription)>(CodecSettings)>(XavcSettings) to the value
     * XAVC_4K_INTRA_VBR.
     */
    private $xavc4kIntraVbrProfileSettings;

    /**
     * Required when you set (Profile) under (VideoDescription)>(CodecSettings)>(XavcSettings) to the value XAVC_4K.
     */
    private $xavc4kProfileSettings;

    /**
     * Required when you set (Profile) under (VideoDescription)>(CodecSettings)>(XavcSettings) to the value
     * XAVC_HD_INTRA_CBG.
     */
    private $xavcHdIntraCbgProfileSettings;

    /**
     * Required when you set (Profile) under (VideoDescription)>(CodecSettings)>(XavcSettings) to the value XAVC_HD.
     */
    private $xavcHdProfileSettings;

    /**
     * @param array{
     *   AdaptiveQuantization?: null|XavcAdaptiveQuantization::*,
     *   EntropyEncoding?: null|XavcEntropyEncoding::*,
     *   FramerateControl?: null|XavcFramerateControl::*,
     *   FramerateConversionAlgorithm?: null|XavcFramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   Profile?: null|XavcProfile::*,
     *   SlowPal?: null|XavcSlowPal::*,
     *   Softness?: null|int,
     *   SpatialAdaptiveQuantization?: null|XavcSpatialAdaptiveQuantization::*,
     *   TemporalAdaptiveQuantization?: null|XavcTemporalAdaptiveQuantization::*,
     *   Xavc4kIntraCbgProfileSettings?: null|Xavc4kIntraCbgProfileSettings|array,
     *   Xavc4kIntraVbrProfileSettings?: null|Xavc4kIntraVbrProfileSettings|array,
     *   Xavc4kProfileSettings?: null|Xavc4kProfileSettings|array,
     *   XavcHdIntraCbgProfileSettings?: null|XavcHdIntraCbgProfileSettings|array,
     *   XavcHdProfileSettings?: null|XavcHdProfileSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->adaptiveQuantization = $input['AdaptiveQuantization'] ?? null;
        $this->entropyEncoding = $input['EntropyEncoding'] ?? null;
        $this->framerateControl = $input['FramerateControl'] ?? null;
        $this->framerateConversionAlgorithm = $input['FramerateConversionAlgorithm'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->profile = $input['Profile'] ?? null;
        $this->slowPal = $input['SlowPal'] ?? null;
        $this->softness = $input['Softness'] ?? null;
        $this->spatialAdaptiveQuantization = $input['SpatialAdaptiveQuantization'] ?? null;
        $this->temporalAdaptiveQuantization = $input['TemporalAdaptiveQuantization'] ?? null;
        $this->xavc4kIntraCbgProfileSettings = isset($input['Xavc4kIntraCbgProfileSettings']) ? Xavc4kIntraCbgProfileSettings::create($input['Xavc4kIntraCbgProfileSettings']) : null;
        $this->xavc4kIntraVbrProfileSettings = isset($input['Xavc4kIntraVbrProfileSettings']) ? Xavc4kIntraVbrProfileSettings::create($input['Xavc4kIntraVbrProfileSettings']) : null;
        $this->xavc4kProfileSettings = isset($input['Xavc4kProfileSettings']) ? Xavc4kProfileSettings::create($input['Xavc4kProfileSettings']) : null;
        $this->xavcHdIntraCbgProfileSettings = isset($input['XavcHdIntraCbgProfileSettings']) ? XavcHdIntraCbgProfileSettings::create($input['XavcHdIntraCbgProfileSettings']) : null;
        $this->xavcHdProfileSettings = isset($input['XavcHdProfileSettings']) ? XavcHdProfileSettings::create($input['XavcHdProfileSettings']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return XavcAdaptiveQuantization::*|null
     */
    public function getAdaptiveQuantization(): ?string
    {
        return $this->adaptiveQuantization;
    }

    /**
     * @return XavcEntropyEncoding::*|null
     */
    public function getEntropyEncoding(): ?string
    {
        return $this->entropyEncoding;
    }

    /**
     * @return XavcFramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return XavcFramerateConversionAlgorithm::*|null
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
     * @return XavcProfile::*|null
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * @return XavcSlowPal::*|null
     */
    public function getSlowPal(): ?string
    {
        return $this->slowPal;
    }

    public function getSoftness(): ?int
    {
        return $this->softness;
    }

    /**
     * @return XavcSpatialAdaptiveQuantization::*|null
     */
    public function getSpatialAdaptiveQuantization(): ?string
    {
        return $this->spatialAdaptiveQuantization;
    }

    /**
     * @return XavcTemporalAdaptiveQuantization::*|null
     */
    public function getTemporalAdaptiveQuantization(): ?string
    {
        return $this->temporalAdaptiveQuantization;
    }

    public function getXavc4kIntraCbgProfileSettings(): ?Xavc4kIntraCbgProfileSettings
    {
        return $this->xavc4kIntraCbgProfileSettings;
    }

    public function getXavc4kIntraVbrProfileSettings(): ?Xavc4kIntraVbrProfileSettings
    {
        return $this->xavc4kIntraVbrProfileSettings;
    }

    public function getXavc4kProfileSettings(): ?Xavc4kProfileSettings
    {
        return $this->xavc4kProfileSettings;
    }

    public function getXavcHdIntraCbgProfileSettings(): ?XavcHdIntraCbgProfileSettings
    {
        return $this->xavcHdIntraCbgProfileSettings;
    }

    public function getXavcHdProfileSettings(): ?XavcHdProfileSettings
    {
        return $this->xavcHdProfileSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->adaptiveQuantization) {
            if (!XavcAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "adaptiveQuantization" for "%s". The value "%s" is not a valid "XavcAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['adaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->entropyEncoding) {
            if (!XavcEntropyEncoding::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "entropyEncoding" for "%s". The value "%s" is not a valid "XavcEntropyEncoding".', __CLASS__, $v));
            }
            $payload['entropyEncoding'] = $v;
        }
        if (null !== $v = $this->framerateControl) {
            if (!XavcFramerateControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "XavcFramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!XavcFramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "XavcFramerateConversionAlgorithm".', __CLASS__, $v));
            }
            $payload['framerateConversionAlgorithm'] = $v;
        }
        if (null !== $v = $this->framerateDenominator) {
            $payload['framerateDenominator'] = $v;
        }
        if (null !== $v = $this->framerateNumerator) {
            $payload['framerateNumerator'] = $v;
        }
        if (null !== $v = $this->profile) {
            if (!XavcProfile::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "profile" for "%s". The value "%s" is not a valid "XavcProfile".', __CLASS__, $v));
            }
            $payload['profile'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!XavcSlowPal::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "XavcSlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->softness) {
            $payload['softness'] = $v;
        }
        if (null !== $v = $this->spatialAdaptiveQuantization) {
            if (!XavcSpatialAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "spatialAdaptiveQuantization" for "%s". The value "%s" is not a valid "XavcSpatialAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['spatialAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->temporalAdaptiveQuantization) {
            if (!XavcTemporalAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "temporalAdaptiveQuantization" for "%s". The value "%s" is not a valid "XavcTemporalAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['temporalAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->xavc4kIntraCbgProfileSettings) {
            $payload['xavc4kIntraCbgProfileSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->xavc4kIntraVbrProfileSettings) {
            $payload['xavc4kIntraVbrProfileSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->xavc4kProfileSettings) {
            $payload['xavc4kProfileSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->xavcHdIntraCbgProfileSettings) {
            $payload['xavcHdIntraCbgProfileSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->xavcHdProfileSettings) {
            $payload['xavcHdProfileSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
