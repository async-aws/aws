<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Mpeg2AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\Mpeg2CodecLevel;
use AsyncAws\MediaConvert\Enum\Mpeg2CodecProfile;
use AsyncAws\MediaConvert\Enum\Mpeg2DynamicSubGop;
use AsyncAws\MediaConvert\Enum\Mpeg2FramerateControl;
use AsyncAws\MediaConvert\Enum\Mpeg2FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Mpeg2GopSizeUnits;
use AsyncAws\MediaConvert\Enum\Mpeg2InterlaceMode;
use AsyncAws\MediaConvert\Enum\Mpeg2IntraDcPrecision;
use AsyncAws\MediaConvert\Enum\Mpeg2ParControl;
use AsyncAws\MediaConvert\Enum\Mpeg2QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\Mpeg2RateControlMode;
use AsyncAws\MediaConvert\Enum\Mpeg2ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\Mpeg2SceneChangeDetect;
use AsyncAws\MediaConvert\Enum\Mpeg2SlowPal;
use AsyncAws\MediaConvert\Enum\Mpeg2SpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\Mpeg2Syntax;
use AsyncAws\MediaConvert\Enum\Mpeg2Telecine;
use AsyncAws\MediaConvert\Enum\Mpeg2TemporalAdaptiveQuantization;

/**
 * Required when you set (Codec) under (VideoDescription)>(CodecSettings) to the value MPEG2.
 */
final class Mpeg2Settings
{
    /**
     * Specify the strength of any adaptive quantization filters that you enable. The value that you choose here applies to
     * the following settings: Spatial adaptive quantization (spatialAdaptiveQuantization), and Temporal adaptive
     * quantization (temporalAdaptiveQuantization).
     */
    private $adaptiveQuantization;

    /**
     * Specify the average bitrate in bits per second. Required for VBR and CBR. For MS Smooth outputs, bitrates must be
     * unique when rounded down to the nearest multiple of 1000.
     */
    private $bitrate;

    /**
     * Use Level (Mpeg2CodecLevel) to set the MPEG-2 level for the video output.
     */
    private $codecLevel;

    /**
     * Use Profile (Mpeg2CodecProfile) to set the MPEG-2 profile for the video output.
     */
    private $codecProfile;

    /**
     * Choose Adaptive to improve subjective video quality for high-motion content. This will cause the service to use fewer
     * B-frames (which infer information based on other frames) for high-motion portions of the video and more B-frames for
     * low-motion portions. The maximum number of B-frames is limited by the value you provide for the setting B frames
     * between reference frames (numberBFramesBetweenReferenceFrames).
     */
    private $dynamicSubGop;

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
     * Specify the relative frequency of open to closed GOPs in this output. For example, if you want to allow four open
     * GOPs and then require a closed GOP, set this value to 5. When you create a streaming output, we recommend that you
     * keep the default value, 1, so that players starting mid-stream receive an IDR frame as quickly as possible. Don't set
     * this value to 0; that would break output segmenting.
     */
    private $gopClosedCadence;

    /**
     * Specify the interval between keyframes, in seconds or frames, for this output. Default: 12 Related settings: When you
     * specify the GOP size in seconds, set GOP mode control (GopSizeUnits) to Specified, seconds (SECONDS). The default
     * value for GOP mode control (GopSizeUnits) is Frames (FRAMES).
     */
    private $gopSize;

    /**
     * Specify the units for GOP size (GopSize). If you don't specify a value here, by default the encoder measures GOP size
     * in frames.
     */
    private $gopSizeUnits;

    /**
     * If your downstream systems have strict buffer requirements: Specify the minimum percentage of the HRD buffer that's
     * available at the end of each encoded video segment. For the best video quality: Set to 0 or leave blank to
     * automatically determine the final buffer fill percentage.
     */
    private $hrdBufferFinalFillPercentage;

    /**
     * Percentage of the buffer that should initially be filled (HRD buffer model).
     */
    private $hrdBufferInitialFillPercentage;

    /**
     * Size of buffer (HRD buffer model) in bits. For example, enter five megabits as 5000000.
     */
    private $hrdBufferSize;

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
     * Use Intra DC precision (Mpeg2IntraDcPrecision) to set quantization precision for intra-block DC coefficients. If you
     * choose the value auto, the service will automatically select the precision based on the per-frame compression ratio.
     */
    private $intraDcPrecision;

    /**
     * Maximum bitrate in bits/second. For example, enter five megabits per second as 5000000.
     */
    private $maxBitrate;

    /**
     * Use this setting only when you also enable Scene change detection (SceneChangeDetect). This setting determines how
     * the encoder manages the spacing between I-frames that it inserts as part of the I-frame cadence and the I-frames that
     * it inserts for Scene change detection. When you specify a value for this setting, the encoder determines whether to
     * skip a cadence-driven I-frame by the value you set. For example, if you set Min I interval (minIInterval) to 5 and a
     * cadence-driven I-frame would fall within 5 frames of a scene-change I-frame, then the encoder skips the
     * cadence-driven I-frame. In this way, one GOP is shrunk slightly and one GOP is stretched slightly. When the
     * cadence-driven I-frames are farther from the scene-change I-frame than the value you set, then the encoder leaves all
     * I-frames in place and the GOPs surrounding the scene change are smaller than the usual cadence GOPs.
     */
    private $minIinterval;

    /**
     * Specify the number of B-frames that MediaConvert puts between reference frames in this output. Valid values are whole
     * numbers from 0 through 7. When you don't specify a value, MediaConvert defaults to 2.
     */
    private $numberBframesBetweenReferenceFrames;

    /**
     * Optional. Specify how the service determines the pixel aspect ratio (PAR) for this output. The default behavior,
     * Follow source (INITIALIZE_FROM_SOURCE), uses the PAR from your input video for your output. To specify a different
     * PAR in the console, choose any value other than Follow source. To specify a different PAR by editing the JSON job
     * specification, choose SPECIFIED. When you choose SPECIFIED for this setting, you must also specify values for the
     * parNumerator and parDenominator settings.
     */
    private $parControl;

    /**
     * Required when you set Pixel aspect ratio (parControl) to SPECIFIED. On the console, this corresponds to any value
     * other than Follow source. When you specify an output pixel aspect ratio (PAR) that is different from your input video
     * PAR, provide your output PAR as a ratio. For example, for D1/DV NTSC widescreen, you would specify the ratio 40:33.
     * In this example, the value for parDenominator is 33.
     */
    private $parDenominator;

    /**
     * Required when you set Pixel aspect ratio (parControl) to SPECIFIED. On the console, this corresponds to any value
     * other than Follow source. When you specify an output pixel aspect ratio (PAR) that is different from your input video
     * PAR, provide your output PAR as a ratio. For example, for D1/DV NTSC widescreen, you would specify the ratio 40:33.
     * In this example, the value for parNumerator is 40.
     */
    private $parNumerator;

    /**
     * Optional. Use Quality tuning level (qualityTuningLevel) to choose how you want to trade off encoding speed for output
     * video quality. The default behavior is faster, lower quality, single-pass encoding.
     */
    private $qualityTuningLevel;

    /**
     * Use Rate control mode (Mpeg2RateControlMode) to specify whether the bitrate is variable (vbr) or constant (cbr).
     */
    private $rateControlMode;

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
     * Enable this setting to insert I-frames at scene changes that the service automatically detects. This improves video
     * quality and is enabled by default.
     */
    private $sceneChangeDetect;

    /**
     * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
     * 25 fps output. When you enable slow PAL, MediaConvert relabels the video frames to 25 fps and resamples your audio to
     * keep it synchronized with the video. Note that enabling this setting will slightly reduce the duration of your video.
     * Required settings: You must also set Framerate to 25. In your JSON job specification, set (framerateControl) to
     * (SPECIFIED), (framerateNumerator) to 25 and (framerateDenominator) to 1.
     */
    private $slowPal;

    /**
     * Ignore this setting unless you need to comply with a specification that requires a specific value. If you don't have
     * a specification requirement, we recommend that you adjust the softness of your output by using a lower value for the
     * setting Sharpness (sharpness) or by enabling a noise reducer filter (noiseReducerFilter). The Softness (softness)
     * setting specifies the quantization matrices that the encoder uses. Keep the default value, 0, to use the AWS
     * Elemental default matrices. Choose a value from 17 to 128 to use planar interpolation. Increasing values from 17 to
     * 128 result in increasing reduction of high-frequency data. The value 128 results in the softest video.
     */
    private $softness;

    /**
     * Keep the default value, Enabled (ENABLED), to adjust quantization within each frame based on spatial variation of
     * content complexity. When you enable this feature, the encoder uses fewer bits on areas that can sustain more
     * distortion with no noticeable visual degradation and uses more bits on areas where any small distortion will be
     * noticeable. For example, complex textured blocks are encoded with fewer bits and smooth textured blocks are encoded
     * with more bits. Enabling this feature will almost always improve your video quality. Note, though, that this feature
     * doesn't take into account where the viewer's attention is likely to be. If viewers are likely to be focusing their
     * attention on a part of the screen with a lot of complex texture, you might choose to disable this feature. Related
     * setting: When you enable spatial adaptive quantization, set the value for Adaptive quantization
     * (adaptiveQuantization) depending on your content. For homogeneous content, such as cartoons and video games, set it
     * to Low. For content with a wider variety of textures, set it to High or Higher.
     */
    private $spatialAdaptiveQuantization;

    /**
     * Specify whether this output's video uses the D10 syntax. Keep the default value to not use the syntax. Related
     * settings: When you choose D10 (D_10) for your MXF profile (profile), you must also set this value to D10 (D_10).
     */
    private $syntax;

    /**
     * When you do frame rate conversion from 23.976 frames per second (fps) to 29.97 fps, and your output scan type is
     * interlaced, you can optionally enable hard or soft telecine to create a smoother picture. Hard telecine (HARD)
     * produces a 29.97i output. Soft telecine (SOFT) produces an output with a 23.976 output that signals to the video
     * player device to do the conversion during play back. When you keep the default value, None (NONE), MediaConvert does
     * a standard frame rate conversion to 29.97 without doing anything with the field polarity to create a smoother
     * picture.
     */
    private $telecine;

    /**
     * Keep the default value, Enabled (ENABLED), to adjust quantization within each frame based on temporal variation of
     * content complexity. When you enable this feature, the encoder uses fewer bits on areas of the frame that aren't
     * moving and uses more bits on complex objects with sharp edges that move a lot. For example, this feature improves the
     * readability of text tickers on newscasts and scoreboards on sports matches. Enabling this feature will almost always
     * improve your video quality. Note, though, that this feature doesn't take into account where the viewer's attention is
     * likely to be. If viewers are likely to be focusing their attention on a part of the screen that doesn't have moving
     * objects with sharp edges, such as sports athletes' faces, you might choose to disable this feature. Related setting:
     * When you enable temporal quantization, adjust the strength of the filter with the setting Adaptive quantization
     * (adaptiveQuantization).
     */
    private $temporalAdaptiveQuantization;

    /**
     * @param array{
     *   AdaptiveQuantization?: null|Mpeg2AdaptiveQuantization::*,
     *   Bitrate?: null|int,
     *   CodecLevel?: null|Mpeg2CodecLevel::*,
     *   CodecProfile?: null|Mpeg2CodecProfile::*,
     *   DynamicSubGop?: null|Mpeg2DynamicSubGop::*,
     *   FramerateControl?: null|Mpeg2FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|Mpeg2FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopClosedCadence?: null|int,
     *   GopSize?: null|float,
     *   GopSizeUnits?: null|Mpeg2GopSizeUnits::*,
     *   HrdBufferFinalFillPercentage?: null|int,
     *   HrdBufferInitialFillPercentage?: null|int,
     *   HrdBufferSize?: null|int,
     *   InterlaceMode?: null|Mpeg2InterlaceMode::*,
     *   IntraDcPrecision?: null|Mpeg2IntraDcPrecision::*,
     *   MaxBitrate?: null|int,
     *   MinIInterval?: null|int,
     *   NumberBFramesBetweenReferenceFrames?: null|int,
     *   ParControl?: null|Mpeg2ParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   QualityTuningLevel?: null|Mpeg2QualityTuningLevel::*,
     *   RateControlMode?: null|Mpeg2RateControlMode::*,
     *   ScanTypeConversionMode?: null|Mpeg2ScanTypeConversionMode::*,
     *   SceneChangeDetect?: null|Mpeg2SceneChangeDetect::*,
     *   SlowPal?: null|Mpeg2SlowPal::*,
     *   Softness?: null|int,
     *   SpatialAdaptiveQuantization?: null|Mpeg2SpatialAdaptiveQuantization::*,
     *   Syntax?: null|Mpeg2Syntax::*,
     *   Telecine?: null|Mpeg2Telecine::*,
     *   TemporalAdaptiveQuantization?: null|Mpeg2TemporalAdaptiveQuantization::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->adaptiveQuantization = $input['AdaptiveQuantization'] ?? null;
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->codecLevel = $input['CodecLevel'] ?? null;
        $this->codecProfile = $input['CodecProfile'] ?? null;
        $this->dynamicSubGop = $input['DynamicSubGop'] ?? null;
        $this->framerateControl = $input['FramerateControl'] ?? null;
        $this->framerateConversionAlgorithm = $input['FramerateConversionAlgorithm'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->gopClosedCadence = $input['GopClosedCadence'] ?? null;
        $this->gopSize = $input['GopSize'] ?? null;
        $this->gopSizeUnits = $input['GopSizeUnits'] ?? null;
        $this->hrdBufferFinalFillPercentage = $input['HrdBufferFinalFillPercentage'] ?? null;
        $this->hrdBufferInitialFillPercentage = $input['HrdBufferInitialFillPercentage'] ?? null;
        $this->hrdBufferSize = $input['HrdBufferSize'] ?? null;
        $this->interlaceMode = $input['InterlaceMode'] ?? null;
        $this->intraDcPrecision = $input['IntraDcPrecision'] ?? null;
        $this->maxBitrate = $input['MaxBitrate'] ?? null;
        $this->minIinterval = $input['MinIInterval'] ?? null;
        $this->numberBframesBetweenReferenceFrames = $input['NumberBFramesBetweenReferenceFrames'] ?? null;
        $this->parControl = $input['ParControl'] ?? null;
        $this->parDenominator = $input['ParDenominator'] ?? null;
        $this->parNumerator = $input['ParNumerator'] ?? null;
        $this->qualityTuningLevel = $input['QualityTuningLevel'] ?? null;
        $this->rateControlMode = $input['RateControlMode'] ?? null;
        $this->scanTypeConversionMode = $input['ScanTypeConversionMode'] ?? null;
        $this->sceneChangeDetect = $input['SceneChangeDetect'] ?? null;
        $this->slowPal = $input['SlowPal'] ?? null;
        $this->softness = $input['Softness'] ?? null;
        $this->spatialAdaptiveQuantization = $input['SpatialAdaptiveQuantization'] ?? null;
        $this->syntax = $input['Syntax'] ?? null;
        $this->telecine = $input['Telecine'] ?? null;
        $this->temporalAdaptiveQuantization = $input['TemporalAdaptiveQuantization'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Mpeg2AdaptiveQuantization::*|null
     */
    public function getAdaptiveQuantization(): ?string
    {
        return $this->adaptiveQuantization;
    }

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * @return Mpeg2CodecLevel::*|null
     */
    public function getCodecLevel(): ?string
    {
        return $this->codecLevel;
    }

    /**
     * @return Mpeg2CodecProfile::*|null
     */
    public function getCodecProfile(): ?string
    {
        return $this->codecProfile;
    }

    /**
     * @return Mpeg2DynamicSubGop::*|null
     */
    public function getDynamicSubGop(): ?string
    {
        return $this->dynamicSubGop;
    }

    /**
     * @return Mpeg2FramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return Mpeg2FramerateConversionAlgorithm::*|null
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

    public function getGopClosedCadence(): ?int
    {
        return $this->gopClosedCadence;
    }

    public function getGopSize(): ?float
    {
        return $this->gopSize;
    }

    /**
     * @return Mpeg2GopSizeUnits::*|null
     */
    public function getGopSizeUnits(): ?string
    {
        return $this->gopSizeUnits;
    }

    public function getHrdBufferFinalFillPercentage(): ?int
    {
        return $this->hrdBufferFinalFillPercentage;
    }

    public function getHrdBufferInitialFillPercentage(): ?int
    {
        return $this->hrdBufferInitialFillPercentage;
    }

    public function getHrdBufferSize(): ?int
    {
        return $this->hrdBufferSize;
    }

    /**
     * @return Mpeg2InterlaceMode::*|null
     */
    public function getInterlaceMode(): ?string
    {
        return $this->interlaceMode;
    }

    /**
     * @return Mpeg2IntraDcPrecision::*|null
     */
    public function getIntraDcPrecision(): ?string
    {
        return $this->intraDcPrecision;
    }

    public function getMaxBitrate(): ?int
    {
        return $this->maxBitrate;
    }

    public function getMinIinterval(): ?int
    {
        return $this->minIinterval;
    }

    public function getNumberBframesBetweenReferenceFrames(): ?int
    {
        return $this->numberBframesBetweenReferenceFrames;
    }

    /**
     * @return Mpeg2ParControl::*|null
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
     * @return Mpeg2QualityTuningLevel::*|null
     */
    public function getQualityTuningLevel(): ?string
    {
        return $this->qualityTuningLevel;
    }

    /**
     * @return Mpeg2RateControlMode::*|null
     */
    public function getRateControlMode(): ?string
    {
        return $this->rateControlMode;
    }

    /**
     * @return Mpeg2ScanTypeConversionMode::*|null
     */
    public function getScanTypeConversionMode(): ?string
    {
        return $this->scanTypeConversionMode;
    }

    /**
     * @return Mpeg2SceneChangeDetect::*|null
     */
    public function getSceneChangeDetect(): ?string
    {
        return $this->sceneChangeDetect;
    }

    /**
     * @return Mpeg2SlowPal::*|null
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
     * @return Mpeg2SpatialAdaptiveQuantization::*|null
     */
    public function getSpatialAdaptiveQuantization(): ?string
    {
        return $this->spatialAdaptiveQuantization;
    }

    /**
     * @return Mpeg2Syntax::*|null
     */
    public function getSyntax(): ?string
    {
        return $this->syntax;
    }

    /**
     * @return Mpeg2Telecine::*|null
     */
    public function getTelecine(): ?string
    {
        return $this->telecine;
    }

    /**
     * @return Mpeg2TemporalAdaptiveQuantization::*|null
     */
    public function getTemporalAdaptiveQuantization(): ?string
    {
        return $this->temporalAdaptiveQuantization;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->adaptiveQuantization) {
            if (!Mpeg2AdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "adaptiveQuantization" for "%s". The value "%s" is not a valid "Mpeg2AdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['adaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->codecLevel) {
            if (!Mpeg2CodecLevel::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "codecLevel" for "%s". The value "%s" is not a valid "Mpeg2CodecLevel".', __CLASS__, $v));
            }
            $payload['codecLevel'] = $v;
        }
        if (null !== $v = $this->codecProfile) {
            if (!Mpeg2CodecProfile::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "codecProfile" for "%s". The value "%s" is not a valid "Mpeg2CodecProfile".', __CLASS__, $v));
            }
            $payload['codecProfile'] = $v;
        }
        if (null !== $v = $this->dynamicSubGop) {
            if (!Mpeg2DynamicSubGop::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "dynamicSubGop" for "%s". The value "%s" is not a valid "Mpeg2DynamicSubGop".', __CLASS__, $v));
            }
            $payload['dynamicSubGop'] = $v;
        }
        if (null !== $v = $this->framerateControl) {
            if (!Mpeg2FramerateControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "Mpeg2FramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!Mpeg2FramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "Mpeg2FramerateConversionAlgorithm".', __CLASS__, $v));
            }
            $payload['framerateConversionAlgorithm'] = $v;
        }
        if (null !== $v = $this->framerateDenominator) {
            $payload['framerateDenominator'] = $v;
        }
        if (null !== $v = $this->framerateNumerator) {
            $payload['framerateNumerator'] = $v;
        }
        if (null !== $v = $this->gopClosedCadence) {
            $payload['gopClosedCadence'] = $v;
        }
        if (null !== $v = $this->gopSize) {
            $payload['gopSize'] = $v;
        }
        if (null !== $v = $this->gopSizeUnits) {
            if (!Mpeg2GopSizeUnits::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "gopSizeUnits" for "%s". The value "%s" is not a valid "Mpeg2GopSizeUnits".', __CLASS__, $v));
            }
            $payload['gopSizeUnits'] = $v;
        }
        if (null !== $v = $this->hrdBufferFinalFillPercentage) {
            $payload['hrdBufferFinalFillPercentage'] = $v;
        }
        if (null !== $v = $this->hrdBufferInitialFillPercentage) {
            $payload['hrdBufferInitialFillPercentage'] = $v;
        }
        if (null !== $v = $this->hrdBufferSize) {
            $payload['hrdBufferSize'] = $v;
        }
        if (null !== $v = $this->interlaceMode) {
            if (!Mpeg2InterlaceMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "Mpeg2InterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
        }
        if (null !== $v = $this->intraDcPrecision) {
            if (!Mpeg2IntraDcPrecision::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "intraDcPrecision" for "%s". The value "%s" is not a valid "Mpeg2IntraDcPrecision".', __CLASS__, $v));
            }
            $payload['intraDcPrecision'] = $v;
        }
        if (null !== $v = $this->maxBitrate) {
            $payload['maxBitrate'] = $v;
        }
        if (null !== $v = $this->minIinterval) {
            $payload['minIInterval'] = $v;
        }
        if (null !== $v = $this->numberBframesBetweenReferenceFrames) {
            $payload['numberBFramesBetweenReferenceFrames'] = $v;
        }
        if (null !== $v = $this->parControl) {
            if (!Mpeg2ParControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "parControl" for "%s". The value "%s" is not a valid "Mpeg2ParControl".', __CLASS__, $v));
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
            if (!Mpeg2QualityTuningLevel::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "qualityTuningLevel" for "%s". The value "%s" is not a valid "Mpeg2QualityTuningLevel".', __CLASS__, $v));
            }
            $payload['qualityTuningLevel'] = $v;
        }
        if (null !== $v = $this->rateControlMode) {
            if (!Mpeg2RateControlMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "rateControlMode" for "%s". The value "%s" is not a valid "Mpeg2RateControlMode".', __CLASS__, $v));
            }
            $payload['rateControlMode'] = $v;
        }
        if (null !== $v = $this->scanTypeConversionMode) {
            if (!Mpeg2ScanTypeConversionMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "scanTypeConversionMode" for "%s". The value "%s" is not a valid "Mpeg2ScanTypeConversionMode".', __CLASS__, $v));
            }
            $payload['scanTypeConversionMode'] = $v;
        }
        if (null !== $v = $this->sceneChangeDetect) {
            if (!Mpeg2SceneChangeDetect::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "sceneChangeDetect" for "%s". The value "%s" is not a valid "Mpeg2SceneChangeDetect".', __CLASS__, $v));
            }
            $payload['sceneChangeDetect'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!Mpeg2SlowPal::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "Mpeg2SlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->softness) {
            $payload['softness'] = $v;
        }
        if (null !== $v = $this->spatialAdaptiveQuantization) {
            if (!Mpeg2SpatialAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "spatialAdaptiveQuantization" for "%s". The value "%s" is not a valid "Mpeg2SpatialAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['spatialAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->syntax) {
            if (!Mpeg2Syntax::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "syntax" for "%s". The value "%s" is not a valid "Mpeg2Syntax".', __CLASS__, $v));
            }
            $payload['syntax'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!Mpeg2Telecine::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "Mpeg2Telecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }
        if (null !== $v = $this->temporalAdaptiveQuantization) {
            if (!Mpeg2TemporalAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "temporalAdaptiveQuantization" for "%s". The value "%s" is not a valid "Mpeg2TemporalAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['temporalAdaptiveQuantization'] = $v;
        }

        return $payload;
    }
}
