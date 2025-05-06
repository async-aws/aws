<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\FrameMetricType;
use AsyncAws\MediaConvert\Enum\H265AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265AlternateTransferFunctionSei;
use AsyncAws\MediaConvert\Enum\H265CodecLevel;
use AsyncAws\MediaConvert\Enum\H265CodecProfile;
use AsyncAws\MediaConvert\Enum\H265Deblocking;
use AsyncAws\MediaConvert\Enum\H265DynamicSubGop;
use AsyncAws\MediaConvert\Enum\H265EndOfStreamMarkers;
use AsyncAws\MediaConvert\Enum\H265FlickerAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265FramerateControl;
use AsyncAws\MediaConvert\Enum\H265FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\H265GopBReference;
use AsyncAws\MediaConvert\Enum\H265GopSizeUnits;
use AsyncAws\MediaConvert\Enum\H265InterlaceMode;
use AsyncAws\MediaConvert\Enum\H265ParControl;
use AsyncAws\MediaConvert\Enum\H265QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\H265RateControlMode;
use AsyncAws\MediaConvert\Enum\H265SampleAdaptiveOffsetFilterMode;
use AsyncAws\MediaConvert\Enum\H265ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\H265SceneChangeDetect;
use AsyncAws\MediaConvert\Enum\H265SlowPal;
use AsyncAws\MediaConvert\Enum\H265SpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265Telecine;
use AsyncAws\MediaConvert\Enum\H265TemporalAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265TemporalIds;
use AsyncAws\MediaConvert\Enum\H265Tiles;
use AsyncAws\MediaConvert\Enum\H265UnregisteredSeiTimecode;
use AsyncAws\MediaConvert\Enum\H265WriteMp4PackagingType;

/**
 * Settings for H265 codec.
 */
final class H265Settings
{
    /**
     * When you set Adaptive Quantization to Auto, or leave blank, MediaConvert automatically applies quantization to
     * improve the video quality of your output. Set Adaptive Quantization to Low, Medium, High, Higher, or Max to manually
     * control the strength of the quantization filter. When you do, you can specify a value for Spatial Adaptive
     * Quantization, Temporal Adaptive Quantization, and Flicker Adaptive Quantization, to further control the quantization
     * filter. Set Adaptive Quantization to Off to apply no quantization to your output.
     *
     * @var H265AdaptiveQuantization::*|null
     */
    private $adaptiveQuantization;

    /**
     * Enables Alternate Transfer Function SEI message for outputs using Hybrid Log Gamma (HLG) Electro-Optical Transfer
     * Function (EOTF).
     *
     * @var H265AlternateTransferFunctionSei::*|null
     */
    private $alternateTransferFunctionSei;

    /**
     * The Bandwidth reduction filter increases the video quality of your output relative to its bitrate. Use to lower the
     * bitrate of your constant quality QVBR output, with little or no perceptual decrease in quality. Or, use to increase
     * the video quality of outputs with other rate control modes relative to the bitrate that you specify. Bandwidth
     * reduction increases further when your input is low quality or noisy. Outputs that use this feature incur pro-tier
     * pricing. When you include Bandwidth reduction filter, you cannot include the Noise reducer preprocessor.
     *
     * @var BandwidthReductionFilter|null
     */
    private $bandwidthReductionFilter;

    /**
     * Specify the average bitrate in bits per second. Required for VBR and CBR. For MS Smooth outputs, bitrates must be
     * unique when rounded down to the nearest multiple of 1000.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * H.265 Level.
     *
     * @var H265CodecLevel::*|null
     */
    private $codecLevel;

    /**
     * Represents the Profile and Tier, per the HEVC (H.265) specification. Selections are grouped as [Profile] / [Tier], so
     * "Main/High" represents Main Profile with High Tier. 4:2:2 profiles are only available with the HEVC 4:2:2 License.
     *
     * @var H265CodecProfile::*|null
     */
    private $codecProfile;

    /**
     * Use Deblocking to improve the video quality of your output by smoothing the edges of macroblock artifacts created
     * during video compression. To reduce blocking artifacts at block boundaries, and improve overall video quality: Keep
     * the default value, Enabled. To not apply any deblocking: Choose Disabled. Visible block edge artifacts might appear
     * in the output, especially at lower bitrates.
     *
     * @var H265Deblocking::*|null
     */
    private $deblocking;

    /**
     * Specify whether to allow the number of B-frames in your output GOP structure to vary or not depending on your input
     * video content. To improve the subjective video quality of your output that has high-motion content: Leave blank or
     * keep the default value Adaptive. MediaConvert will use fewer B-frames for high-motion video content than low-motion
     * content. The maximum number of B- frames is limited by the value that you choose for B-frames between reference
     * frames. To use the same number B-frames for all types of content: Choose Static.
     *
     * @var H265DynamicSubGop::*|null
     */
    private $dynamicSubGop;

    /**
     * Optionally include or suppress markers at the end of your output that signal the end of the video stream. To include
     * end of stream markers: Leave blank or keep the default value, Include. To not include end of stream markers: Choose
     * Suppress. This is useful when your output will be inserted into another stream.
     *
     * @var H265EndOfStreamMarkers::*|null
     */
    private $endOfStreamMarkers;

    /**
     * Enable this setting to have the encoder reduce I-frame pop. I-frame pop appears as a visual flicker that can arise
     * when the encoder saves bits by copying some macroblocks many times from frame to frame, and then refreshes them at
     * the I-frame. When you enable this setting, the encoder updates these macroblocks slightly more often to smooth out
     * the flicker. This setting is disabled by default. Related setting: In addition to enabling this setting, you must
     * also set adaptiveQuantization to a value other than Off.
     *
     * @var H265FlickerAdaptiveQuantization::*|null
     */
    private $flickerAdaptiveQuantization;

    /**
     * Use the Framerate setting to specify the frame rate for this output. If you want to keep the same frame rate as the
     * input video, choose Follow source. If you want to do frame rate conversion, choose a frame rate from the dropdown
     * list or choose Custom. The framerates shown in the dropdown list are decimal approximations of fractions. If you
     * choose Custom, specify your frame rate as a fraction.
     *
     * @var H265FramerateControl::*|null
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
     * @var H265FramerateConversionAlgorithm::*|null
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
     * Specify whether to allow B-frames to be referenced by other frame types. To use reference B-frames when your GOP
     * structure has 1 or more B-frames: Leave blank or keep the default value Enabled. We recommend that you choose Enabled
     * to help improve the video quality of your output relative to its bitrate. To not use reference B-frames: Choose
     * Disabled.
     *
     * @var H265GopBReference::*|null
     */
    private $gopBreference;

    /**
     * Specify the relative frequency of open to closed GOPs in this output. For example, if you want to allow four open
     * GOPs and then require a closed GOP, set this value to 5. We recommend that you have the transcoder automatically
     * choose this value for you based on characteristics of your input video. To enable this automatic behavior, do this by
     * keeping the default empty value. If you do explicitly specify a value, for segmented outputs, don't set this value to
     * 0.
     *
     * @var int|null
     */
    private $gopClosedCadence;

    /**
     * Use this setting only when you set GOP mode control to Specified, frames or Specified, seconds. Specify the GOP
     * length using a whole number of frames or a decimal value of seconds. MediaConvert will interpret this value as frames
     * or seconds depending on the value you choose for GOP mode control. If you want to allow MediaConvert to automatically
     * determine GOP size, leave GOP size blank and set GOP mode control to Auto. If your output group specifies HLS, DASH,
     * or CMAF, leave GOP size blank and set GOP mode control to Auto in each output in your output group.
     *
     * @var float|null
     */
    private $gopSize;

    /**
     * Specify how the transcoder determines GOP size for this output. We recommend that you have the transcoder
     * automatically choose this value for you based on characteristics of your input video. To enable this automatic
     * behavior, choose Auto and and leave GOP size blank. By default, if you don't specify GOP mode control, MediaConvert
     * will use automatic behavior. If your output group specifies HLS, DASH, or CMAF, set GOP mode control to Auto and
     * leave GOP size blank in each output in your output group. To explicitly specify the GOP length, choose Specified,
     * frames or Specified, seconds and then provide the GOP length in the related setting GOP size.
     *
     * @var H265GopSizeUnits::*|null
     */
    private $gopSizeUnits;

    /**
     * If your downstream systems have strict buffer requirements: Specify the minimum percentage of the HRD buffer that's
     * available at the end of each encoded video segment. For the best video quality: Set to 0 or leave blank to
     * automatically determine the final buffer fill percentage.
     *
     * @var int|null
     */
    private $hrdBufferFinalFillPercentage;

    /**
     * Percentage of the buffer that should initially be filled (HRD buffer model).
     *
     * @var int|null
     */
    private $hrdBufferInitialFillPercentage;

    /**
     * Size of buffer (HRD buffer model) in bits. For example, enter five megabits as 5000000.
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
     * @var H265InterlaceMode::*|null
     */
    private $interlaceMode;

    /**
     * Maximum bitrate in bits/second. For example, enter five megabits per second as 5000000. Required when Rate control
     * mode is QVBR.
     *
     * @var int|null
     */
    private $maxBitrate;

    /**
     * Specify the minimum number of frames allowed between two IDR-frames in your output. This includes frames created at
     * the start of a GOP or a scene change. Use Min I-Interval to improve video compression by varying GOP size when two
     * IDR-frames would be created near each other. For example, if a regular cadence-driven IDR-frame would fall within 5
     * frames of a scene-change IDR-frame, and you set Min I-interval to 5, then the encoder would only write an IDR-frame
     * for the scene-change. In this way, one GOP is shortened or extended. If a cadence-driven IDR-frame would be further
     * than 5 frames from a scene-change IDR-frame, then the encoder leaves all IDR-frames in place. To use an automatically
     * determined interval: We recommend that you keep this value blank. This allows for MediaConvert to use an optimal
     * setting according to the characteristics of your input video, and results in better video compression. To manually
     * specify an interval: Enter a value from 1 to 30. Use when your downstream systems have specific GOP size
     * requirements. To disable GOP size variance: Enter 0. MediaConvert will only create IDR-frames at the start of your
     * output's cadence-driven GOP. Use when your downstream systems require a regular GOP size.
     *
     * @var int|null
     */
    private $minIinterval;

    /**
     * Specify the number of B-frames between reference frames in this output. For the best video quality: Leave blank.
     * MediaConvert automatically determines the number of B-frames to use based on the characteristics of your input video.
     * To manually specify the number of B-frames between reference frames: Enter an integer from 0 to 7.
     *
     * @var int|null
     */
    private $numberBframesBetweenReferenceFrames;

    /**
     * Number of reference frames to use. The encoder may use more than requested if using B-frames and/or interlaced
     * encoding.
     *
     * @var int|null
     */
    private $numberReferenceFrames;

    /**
     * Optional. Specify how the service determines the pixel aspect ratio (PAR) for this output. The default behavior,
     * Follow source, uses the PAR from your input video for your output. To specify a different PAR, choose any value other
     * than Follow source. When you choose SPECIFIED for this setting, you must also specify values for the parNumerator and
     * parDenominator settings.
     *
     * @var H265ParControl::*|null
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
     * Optionally choose one or more per frame metric reports to generate along with your output. You can use these metrics
     * to analyze your video output according to one or more commonly used image quality metrics. You can specify per frame
     * metrics for output groups or for individual outputs. When you do, MediaConvert writes a CSV (Comma-Separated Values)
     * file to your S3 output destination, named after the video, video codec, and metric type. For example:
     * video_h264_PSNR.csv Jobs that generate per frame metrics will take longer to complete, depending on the resolution
     * and complexity of your output. For example, some 4K jobs might take up to twice as long to complete. Note that when
     * analyzing the video quality of your output, or when comparing the video quality of multiple different outputs, we
     * generally also recommend a detailed visual review in a controlled environment. You can choose from the following per
     * frame metrics: * PSNR: Peak Signal-to-Noise Ratio * SSIM: Structural Similarity Index Measure * MS_SSIM: Multi-Scale
     * Similarity Index Measure * PSNR_HVS: Peak Signal-to-Noise Ratio, Human Visual System * VMAF: Video Multi-Method
     * Assessment Fusion * QVBR: Quality-Defined Variable Bitrate. This option is only available when your output uses the
     * QVBR rate control mode.
     *
     * @var list<FrameMetricType::*>|null
     */
    private $perFrameMetrics;

    /**
     * Optional. Use Quality tuning level to choose how you want to trade off encoding speed for output video quality. The
     * default behavior is faster, lower quality, single-pass encoding.
     *
     * @var H265QualityTuningLevel::*|null
     */
    private $qualityTuningLevel;

    /**
     * Settings for quality-defined variable bitrate encoding with the H.265 codec. Use these settings only when you set
     * QVBR for Rate control mode.
     *
     * @var H265QvbrSettings|null
     */
    private $qvbrSettings;

    /**
     * Use this setting to specify whether this output has a variable bitrate (VBR), constant bitrate (CBR) or
     * quality-defined variable bitrate (QVBR).
     *
     * @var H265RateControlMode::*|null
     */
    private $rateControlMode;

    /**
     * Specify Sample Adaptive Offset (SAO) filter strength. Adaptive mode dynamically selects best strength based on
     * content.
     *
     * @var H265SampleAdaptiveOffsetFilterMode::*|null
     */
    private $sampleAdaptiveOffsetFilterMode;

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
     * @var H265ScanTypeConversionMode::*|null
     */
    private $scanTypeConversionMode;

    /**
     * Enable this setting to insert I-frames at scene changes that the service automatically detects. This improves video
     * quality and is enabled by default. If this output uses QVBR, choose Transition detection for further video quality
     * improvement. For more information about QVBR, see https://docs.aws.amazon.com/console/mediaconvert/cbr-vbr-qvbr.
     *
     * @var H265SceneChangeDetect::*|null
     */
    private $sceneChangeDetect;

    /**
     * Number of slices per picture. Must be less than or equal to the number of macroblock rows for progressive pictures,
     * and less than or equal to half the number of macroblock rows for interlaced pictures.
     *
     * @var int|null
     */
    private $slices;

    /**
     * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
     * 25 fps output. When you enable slow PAL, MediaConvert relabels the video frames to 25 fps and resamples your audio to
     * keep it synchronized with the video. Note that enabling this setting will slightly reduce the duration of your video.
     * Required settings: You must also set Framerate to 25.
     *
     * @var H265SlowPal::*|null
     */
    private $slowPal;

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
     * @var H265SpatialAdaptiveQuantization::*|null
     */
    private $spatialAdaptiveQuantization;

    /**
     * This field applies only if the Streams > Advanced > Framerate field is set to 29.970. This field works with the
     * Streams > Advanced > Preprocessors > Deinterlacer field and the Streams > Advanced > Interlaced Mode field to
     * identify the scan type for the output: Progressive, Interlaced, Hard Telecine or Soft Telecine. - Hard: produces
     * 29.97i output from 23.976 input. - Soft: produces 23.976; the player converts this output to 29.97i.
     *
     * @var H265Telecine::*|null
     */
    private $telecine;

    /**
     * Keep the default value, Enabled, to adjust quantization within each frame based on temporal variation of content
     * complexity. When you enable this feature, the encoder uses fewer bits on areas of the frame that aren't moving and
     * uses more bits on complex objects with sharp edges that move a lot. For example, this feature improves the
     * readability of text tickers on newscasts and scoreboards on sports matches. Enabling this feature will almost always
     * improve your video quality. Note, though, that this feature doesn't take into account where the viewer's attention is
     * likely to be. If viewers are likely to be focusing their attention on a part of the screen that doesn't have moving
     * objects with sharp edges, such as sports athletes' faces, you might choose to disable this feature. Related setting:
     * When you enable temporal quantization, adjust the strength of the filter with the setting Adaptive quantization.
     *
     * @var H265TemporalAdaptiveQuantization::*|null
     */
    private $temporalAdaptiveQuantization;

    /**
     * Enables temporal layer identifiers in the encoded bitstream. Up to 3 layers are supported depending on GOP structure:
     * I- and P-frames form one layer, reference B-frames can form a second layer and non-reference b-frames can form a
     * third layer. Decoders can optionally decode only the lower temporal layers to generate a lower frame rate output. For
     * example, given a bitstream with temporal IDs and with b-frames = 1 (i.e. IbPbPb display order), a decoder could
     * decode all the frames for full frame rate output or only the I and P frames (lowest temporal layer) for a half frame
     * rate output.
     *
     * @var H265TemporalIds::*|null
     */
    private $temporalIds;

    /**
     * Enable use of tiles, allowing horizontal as well as vertical subdivision of the encoded pictures.
     *
     * @var H265Tiles::*|null
     */
    private $tiles;

    /**
     * Inserts timecode for each frame as 4 bytes of an unregistered SEI message.
     *
     * @var H265UnregisteredSeiTimecode::*|null
     */
    private $unregisteredSeiTimecode;

    /**
     * If the location of parameter set NAL units doesn't matter in your workflow, ignore this setting. Use this setting
     * only with CMAF or DASH outputs, or with standalone file outputs in an MPEG-4 container (MP4 outputs). Choose HVC1 to
     * mark your output as HVC1. This makes your output compliant with the following specification: ISO IECJTC1 SC29 N13798
     * Text ISO/IEC FDIS 14496-15 3rd Edition. For these outputs, the service stores parameter set NAL units in the sample
     * headers but not in the samples directly. For MP4 outputs, when you choose HVC1, your output video might not work
     * properly with some downstream systems and video players. The service defaults to marking your output as HEV1. For
     * these outputs, the service writes parameter set NAL units directly into the samples.
     *
     * @var H265WriteMp4PackagingType::*|null
     */
    private $writeMp4PackagingType;

    /**
     * @param array{
     *   AdaptiveQuantization?: null|H265AdaptiveQuantization::*,
     *   AlternateTransferFunctionSei?: null|H265AlternateTransferFunctionSei::*,
     *   BandwidthReductionFilter?: null|BandwidthReductionFilter|array,
     *   Bitrate?: null|int,
     *   CodecLevel?: null|H265CodecLevel::*,
     *   CodecProfile?: null|H265CodecProfile::*,
     *   Deblocking?: null|H265Deblocking::*,
     *   DynamicSubGop?: null|H265DynamicSubGop::*,
     *   EndOfStreamMarkers?: null|H265EndOfStreamMarkers::*,
     *   FlickerAdaptiveQuantization?: null|H265FlickerAdaptiveQuantization::*,
     *   FramerateControl?: null|H265FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|H265FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopBReference?: null|H265GopBReference::*,
     *   GopClosedCadence?: null|int,
     *   GopSize?: null|float,
     *   GopSizeUnits?: null|H265GopSizeUnits::*,
     *   HrdBufferFinalFillPercentage?: null|int,
     *   HrdBufferInitialFillPercentage?: null|int,
     *   HrdBufferSize?: null|int,
     *   InterlaceMode?: null|H265InterlaceMode::*,
     *   MaxBitrate?: null|int,
     *   MinIInterval?: null|int,
     *   NumberBFramesBetweenReferenceFrames?: null|int,
     *   NumberReferenceFrames?: null|int,
     *   ParControl?: null|H265ParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
     *   QualityTuningLevel?: null|H265QualityTuningLevel::*,
     *   QvbrSettings?: null|H265QvbrSettings|array,
     *   RateControlMode?: null|H265RateControlMode::*,
     *   SampleAdaptiveOffsetFilterMode?: null|H265SampleAdaptiveOffsetFilterMode::*,
     *   ScanTypeConversionMode?: null|H265ScanTypeConversionMode::*,
     *   SceneChangeDetect?: null|H265SceneChangeDetect::*,
     *   Slices?: null|int,
     *   SlowPal?: null|H265SlowPal::*,
     *   SpatialAdaptiveQuantization?: null|H265SpatialAdaptiveQuantization::*,
     *   Telecine?: null|H265Telecine::*,
     *   TemporalAdaptiveQuantization?: null|H265TemporalAdaptiveQuantization::*,
     *   TemporalIds?: null|H265TemporalIds::*,
     *   Tiles?: null|H265Tiles::*,
     *   UnregisteredSeiTimecode?: null|H265UnregisteredSeiTimecode::*,
     *   WriteMp4PackagingType?: null|H265WriteMp4PackagingType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->adaptiveQuantization = $input['AdaptiveQuantization'] ?? null;
        $this->alternateTransferFunctionSei = $input['AlternateTransferFunctionSei'] ?? null;
        $this->bandwidthReductionFilter = isset($input['BandwidthReductionFilter']) ? BandwidthReductionFilter::create($input['BandwidthReductionFilter']) : null;
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->codecLevel = $input['CodecLevel'] ?? null;
        $this->codecProfile = $input['CodecProfile'] ?? null;
        $this->deblocking = $input['Deblocking'] ?? null;
        $this->dynamicSubGop = $input['DynamicSubGop'] ?? null;
        $this->endOfStreamMarkers = $input['EndOfStreamMarkers'] ?? null;
        $this->flickerAdaptiveQuantization = $input['FlickerAdaptiveQuantization'] ?? null;
        $this->framerateControl = $input['FramerateControl'] ?? null;
        $this->framerateConversionAlgorithm = $input['FramerateConversionAlgorithm'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->gopBreference = $input['GopBReference'] ?? null;
        $this->gopClosedCadence = $input['GopClosedCadence'] ?? null;
        $this->gopSize = $input['GopSize'] ?? null;
        $this->gopSizeUnits = $input['GopSizeUnits'] ?? null;
        $this->hrdBufferFinalFillPercentage = $input['HrdBufferFinalFillPercentage'] ?? null;
        $this->hrdBufferInitialFillPercentage = $input['HrdBufferInitialFillPercentage'] ?? null;
        $this->hrdBufferSize = $input['HrdBufferSize'] ?? null;
        $this->interlaceMode = $input['InterlaceMode'] ?? null;
        $this->maxBitrate = $input['MaxBitrate'] ?? null;
        $this->minIinterval = $input['MinIInterval'] ?? null;
        $this->numberBframesBetweenReferenceFrames = $input['NumberBFramesBetweenReferenceFrames'] ?? null;
        $this->numberReferenceFrames = $input['NumberReferenceFrames'] ?? null;
        $this->parControl = $input['ParControl'] ?? null;
        $this->parDenominator = $input['ParDenominator'] ?? null;
        $this->parNumerator = $input['ParNumerator'] ?? null;
        $this->perFrameMetrics = $input['PerFrameMetrics'] ?? null;
        $this->qualityTuningLevel = $input['QualityTuningLevel'] ?? null;
        $this->qvbrSettings = isset($input['QvbrSettings']) ? H265QvbrSettings::create($input['QvbrSettings']) : null;
        $this->rateControlMode = $input['RateControlMode'] ?? null;
        $this->sampleAdaptiveOffsetFilterMode = $input['SampleAdaptiveOffsetFilterMode'] ?? null;
        $this->scanTypeConversionMode = $input['ScanTypeConversionMode'] ?? null;
        $this->sceneChangeDetect = $input['SceneChangeDetect'] ?? null;
        $this->slices = $input['Slices'] ?? null;
        $this->slowPal = $input['SlowPal'] ?? null;
        $this->spatialAdaptiveQuantization = $input['SpatialAdaptiveQuantization'] ?? null;
        $this->telecine = $input['Telecine'] ?? null;
        $this->temporalAdaptiveQuantization = $input['TemporalAdaptiveQuantization'] ?? null;
        $this->temporalIds = $input['TemporalIds'] ?? null;
        $this->tiles = $input['Tiles'] ?? null;
        $this->unregisteredSeiTimecode = $input['UnregisteredSeiTimecode'] ?? null;
        $this->writeMp4PackagingType = $input['WriteMp4PackagingType'] ?? null;
    }

    /**
     * @param array{
     *   AdaptiveQuantization?: null|H265AdaptiveQuantization::*,
     *   AlternateTransferFunctionSei?: null|H265AlternateTransferFunctionSei::*,
     *   BandwidthReductionFilter?: null|BandwidthReductionFilter|array,
     *   Bitrate?: null|int,
     *   CodecLevel?: null|H265CodecLevel::*,
     *   CodecProfile?: null|H265CodecProfile::*,
     *   Deblocking?: null|H265Deblocking::*,
     *   DynamicSubGop?: null|H265DynamicSubGop::*,
     *   EndOfStreamMarkers?: null|H265EndOfStreamMarkers::*,
     *   FlickerAdaptiveQuantization?: null|H265FlickerAdaptiveQuantization::*,
     *   FramerateControl?: null|H265FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|H265FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopBReference?: null|H265GopBReference::*,
     *   GopClosedCadence?: null|int,
     *   GopSize?: null|float,
     *   GopSizeUnits?: null|H265GopSizeUnits::*,
     *   HrdBufferFinalFillPercentage?: null|int,
     *   HrdBufferInitialFillPercentage?: null|int,
     *   HrdBufferSize?: null|int,
     *   InterlaceMode?: null|H265InterlaceMode::*,
     *   MaxBitrate?: null|int,
     *   MinIInterval?: null|int,
     *   NumberBFramesBetweenReferenceFrames?: null|int,
     *   NumberReferenceFrames?: null|int,
     *   ParControl?: null|H265ParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
     *   QualityTuningLevel?: null|H265QualityTuningLevel::*,
     *   QvbrSettings?: null|H265QvbrSettings|array,
     *   RateControlMode?: null|H265RateControlMode::*,
     *   SampleAdaptiveOffsetFilterMode?: null|H265SampleAdaptiveOffsetFilterMode::*,
     *   ScanTypeConversionMode?: null|H265ScanTypeConversionMode::*,
     *   SceneChangeDetect?: null|H265SceneChangeDetect::*,
     *   Slices?: null|int,
     *   SlowPal?: null|H265SlowPal::*,
     *   SpatialAdaptiveQuantization?: null|H265SpatialAdaptiveQuantization::*,
     *   Telecine?: null|H265Telecine::*,
     *   TemporalAdaptiveQuantization?: null|H265TemporalAdaptiveQuantization::*,
     *   TemporalIds?: null|H265TemporalIds::*,
     *   Tiles?: null|H265Tiles::*,
     *   UnregisteredSeiTimecode?: null|H265UnregisteredSeiTimecode::*,
     *   WriteMp4PackagingType?: null|H265WriteMp4PackagingType::*,
     * }|H265Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return H265AdaptiveQuantization::*|null
     */
    public function getAdaptiveQuantization(): ?string
    {
        return $this->adaptiveQuantization;
    }

    /**
     * @return H265AlternateTransferFunctionSei::*|null
     */
    public function getAlternateTransferFunctionSei(): ?string
    {
        return $this->alternateTransferFunctionSei;
    }

    public function getBandwidthReductionFilter(): ?BandwidthReductionFilter
    {
        return $this->bandwidthReductionFilter;
    }

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * @return H265CodecLevel::*|null
     */
    public function getCodecLevel(): ?string
    {
        return $this->codecLevel;
    }

    /**
     * @return H265CodecProfile::*|null
     */
    public function getCodecProfile(): ?string
    {
        return $this->codecProfile;
    }

    /**
     * @return H265Deblocking::*|null
     */
    public function getDeblocking(): ?string
    {
        return $this->deblocking;
    }

    /**
     * @return H265DynamicSubGop::*|null
     */
    public function getDynamicSubGop(): ?string
    {
        return $this->dynamicSubGop;
    }

    /**
     * @return H265EndOfStreamMarkers::*|null
     */
    public function getEndOfStreamMarkers(): ?string
    {
        return $this->endOfStreamMarkers;
    }

    /**
     * @return H265FlickerAdaptiveQuantization::*|null
     */
    public function getFlickerAdaptiveQuantization(): ?string
    {
        return $this->flickerAdaptiveQuantization;
    }

    /**
     * @return H265FramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return H265FramerateConversionAlgorithm::*|null
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
     * @return H265GopBReference::*|null
     */
    public function getGopBreference(): ?string
    {
        return $this->gopBreference;
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
     * @return H265GopSizeUnits::*|null
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
     * @return H265InterlaceMode::*|null
     */
    public function getInterlaceMode(): ?string
    {
        return $this->interlaceMode;
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

    public function getNumberReferenceFrames(): ?int
    {
        return $this->numberReferenceFrames;
    }

    /**
     * @return H265ParControl::*|null
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
     * @return list<FrameMetricType::*>
     */
    public function getPerFrameMetrics(): array
    {
        return $this->perFrameMetrics ?? [];
    }

    /**
     * @return H265QualityTuningLevel::*|null
     */
    public function getQualityTuningLevel(): ?string
    {
        return $this->qualityTuningLevel;
    }

    public function getQvbrSettings(): ?H265QvbrSettings
    {
        return $this->qvbrSettings;
    }

    /**
     * @return H265RateControlMode::*|null
     */
    public function getRateControlMode(): ?string
    {
        return $this->rateControlMode;
    }

    /**
     * @return H265SampleAdaptiveOffsetFilterMode::*|null
     */
    public function getSampleAdaptiveOffsetFilterMode(): ?string
    {
        return $this->sampleAdaptiveOffsetFilterMode;
    }

    /**
     * @return H265ScanTypeConversionMode::*|null
     */
    public function getScanTypeConversionMode(): ?string
    {
        return $this->scanTypeConversionMode;
    }

    /**
     * @return H265SceneChangeDetect::*|null
     */
    public function getSceneChangeDetect(): ?string
    {
        return $this->sceneChangeDetect;
    }

    public function getSlices(): ?int
    {
        return $this->slices;
    }

    /**
     * @return H265SlowPal::*|null
     */
    public function getSlowPal(): ?string
    {
        return $this->slowPal;
    }

    /**
     * @return H265SpatialAdaptiveQuantization::*|null
     */
    public function getSpatialAdaptiveQuantization(): ?string
    {
        return $this->spatialAdaptiveQuantization;
    }

    /**
     * @return H265Telecine::*|null
     */
    public function getTelecine(): ?string
    {
        return $this->telecine;
    }

    /**
     * @return H265TemporalAdaptiveQuantization::*|null
     */
    public function getTemporalAdaptiveQuantization(): ?string
    {
        return $this->temporalAdaptiveQuantization;
    }

    /**
     * @return H265TemporalIds::*|null
     */
    public function getTemporalIds(): ?string
    {
        return $this->temporalIds;
    }

    /**
     * @return H265Tiles::*|null
     */
    public function getTiles(): ?string
    {
        return $this->tiles;
    }

    /**
     * @return H265UnregisteredSeiTimecode::*|null
     */
    public function getUnregisteredSeiTimecode(): ?string
    {
        return $this->unregisteredSeiTimecode;
    }

    /**
     * @return H265WriteMp4PackagingType::*|null
     */
    public function getWriteMp4PackagingType(): ?string
    {
        return $this->writeMp4PackagingType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->adaptiveQuantization) {
            if (!H265AdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "adaptiveQuantization" for "%s". The value "%s" is not a valid "H265AdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['adaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->alternateTransferFunctionSei) {
            if (!H265AlternateTransferFunctionSei::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "alternateTransferFunctionSei" for "%s". The value "%s" is not a valid "H265AlternateTransferFunctionSei".', __CLASS__, $v));
            }
            $payload['alternateTransferFunctionSei'] = $v;
        }
        if (null !== $v = $this->bandwidthReductionFilter) {
            $payload['bandwidthReductionFilter'] = $v->requestBody();
        }
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->codecLevel) {
            if (!H265CodecLevel::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codecLevel" for "%s". The value "%s" is not a valid "H265CodecLevel".', __CLASS__, $v));
            }
            $payload['codecLevel'] = $v;
        }
        if (null !== $v = $this->codecProfile) {
            if (!H265CodecProfile::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codecProfile" for "%s". The value "%s" is not a valid "H265CodecProfile".', __CLASS__, $v));
            }
            $payload['codecProfile'] = $v;
        }
        if (null !== $v = $this->deblocking) {
            if (!H265Deblocking::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "deblocking" for "%s". The value "%s" is not a valid "H265Deblocking".', __CLASS__, $v));
            }
            $payload['deblocking'] = $v;
        }
        if (null !== $v = $this->dynamicSubGop) {
            if (!H265DynamicSubGop::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicSubGop" for "%s". The value "%s" is not a valid "H265DynamicSubGop".', __CLASS__, $v));
            }
            $payload['dynamicSubGop'] = $v;
        }
        if (null !== $v = $this->endOfStreamMarkers) {
            if (!H265EndOfStreamMarkers::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "endOfStreamMarkers" for "%s". The value "%s" is not a valid "H265EndOfStreamMarkers".', __CLASS__, $v));
            }
            $payload['endOfStreamMarkers'] = $v;
        }
        if (null !== $v = $this->flickerAdaptiveQuantization) {
            if (!H265FlickerAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "flickerAdaptiveQuantization" for "%s". The value "%s" is not a valid "H265FlickerAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['flickerAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->framerateControl) {
            if (!H265FramerateControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "H265FramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!H265FramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "H265FramerateConversionAlgorithm".', __CLASS__, $v));
            }
            $payload['framerateConversionAlgorithm'] = $v;
        }
        if (null !== $v = $this->framerateDenominator) {
            $payload['framerateDenominator'] = $v;
        }
        if (null !== $v = $this->framerateNumerator) {
            $payload['framerateNumerator'] = $v;
        }
        if (null !== $v = $this->gopBreference) {
            if (!H265GopBReference::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "gopBReference" for "%s". The value "%s" is not a valid "H265GopBReference".', __CLASS__, $v));
            }
            $payload['gopBReference'] = $v;
        }
        if (null !== $v = $this->gopClosedCadence) {
            $payload['gopClosedCadence'] = $v;
        }
        if (null !== $v = $this->gopSize) {
            $payload['gopSize'] = $v;
        }
        if (null !== $v = $this->gopSizeUnits) {
            if (!H265GopSizeUnits::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "gopSizeUnits" for "%s". The value "%s" is not a valid "H265GopSizeUnits".', __CLASS__, $v));
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
            if (!H265InterlaceMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "H265InterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
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
        if (null !== $v = $this->numberReferenceFrames) {
            $payload['numberReferenceFrames'] = $v;
        }
        if (null !== $v = $this->parControl) {
            if (!H265ParControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "parControl" for "%s". The value "%s" is not a valid "H265ParControl".', __CLASS__, $v));
            }
            $payload['parControl'] = $v;
        }
        if (null !== $v = $this->parDenominator) {
            $payload['parDenominator'] = $v;
        }
        if (null !== $v = $this->parNumerator) {
            $payload['parNumerator'] = $v;
        }
        if (null !== $v = $this->perFrameMetrics) {
            $index = -1;
            $payload['perFrameMetrics'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!FrameMetricType::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "perFrameMetrics" for "%s". The value "%s" is not a valid "FrameMetricType".', __CLASS__, $listValue));
                }
                $payload['perFrameMetrics'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->qualityTuningLevel) {
            if (!H265QualityTuningLevel::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "qualityTuningLevel" for "%s". The value "%s" is not a valid "H265QualityTuningLevel".', __CLASS__, $v));
            }
            $payload['qualityTuningLevel'] = $v;
        }
        if (null !== $v = $this->qvbrSettings) {
            $payload['qvbrSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->rateControlMode) {
            if (!H265RateControlMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "rateControlMode" for "%s". The value "%s" is not a valid "H265RateControlMode".', __CLASS__, $v));
            }
            $payload['rateControlMode'] = $v;
        }
        if (null !== $v = $this->sampleAdaptiveOffsetFilterMode) {
            if (!H265SampleAdaptiveOffsetFilterMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "sampleAdaptiveOffsetFilterMode" for "%s". The value "%s" is not a valid "H265SampleAdaptiveOffsetFilterMode".', __CLASS__, $v));
            }
            $payload['sampleAdaptiveOffsetFilterMode'] = $v;
        }
        if (null !== $v = $this->scanTypeConversionMode) {
            if (!H265ScanTypeConversionMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scanTypeConversionMode" for "%s". The value "%s" is not a valid "H265ScanTypeConversionMode".', __CLASS__, $v));
            }
            $payload['scanTypeConversionMode'] = $v;
        }
        if (null !== $v = $this->sceneChangeDetect) {
            if (!H265SceneChangeDetect::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "sceneChangeDetect" for "%s". The value "%s" is not a valid "H265SceneChangeDetect".', __CLASS__, $v));
            }
            $payload['sceneChangeDetect'] = $v;
        }
        if (null !== $v = $this->slices) {
            $payload['slices'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!H265SlowPal::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "H265SlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->spatialAdaptiveQuantization) {
            if (!H265SpatialAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "spatialAdaptiveQuantization" for "%s". The value "%s" is not a valid "H265SpatialAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['spatialAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!H265Telecine::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "H265Telecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }
        if (null !== $v = $this->temporalAdaptiveQuantization) {
            if (!H265TemporalAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "temporalAdaptiveQuantization" for "%s". The value "%s" is not a valid "H265TemporalAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['temporalAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->temporalIds) {
            if (!H265TemporalIds::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "temporalIds" for "%s". The value "%s" is not a valid "H265TemporalIds".', __CLASS__, $v));
            }
            $payload['temporalIds'] = $v;
        }
        if (null !== $v = $this->tiles) {
            if (!H265Tiles::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "tiles" for "%s". The value "%s" is not a valid "H265Tiles".', __CLASS__, $v));
            }
            $payload['tiles'] = $v;
        }
        if (null !== $v = $this->unregisteredSeiTimecode) {
            if (!H265UnregisteredSeiTimecode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "unregisteredSeiTimecode" for "%s". The value "%s" is not a valid "H265UnregisteredSeiTimecode".', __CLASS__, $v));
            }
            $payload['unregisteredSeiTimecode'] = $v;
        }
        if (null !== $v = $this->writeMp4PackagingType) {
            if (!H265WriteMp4PackagingType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "writeMp4PackagingType" for "%s". The value "%s" is not a valid "H265WriteMp4PackagingType".', __CLASS__, $v));
            }
            $payload['writeMp4PackagingType'] = $v;
        }

        return $payload;
    }
}
