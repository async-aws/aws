<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\FrameMetricType;
use AsyncAws\MediaConvert\Enum\H264AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264CodecLevel;
use AsyncAws\MediaConvert\Enum\H264CodecProfile;
use AsyncAws\MediaConvert\Enum\H264DynamicSubGop;
use AsyncAws\MediaConvert\Enum\H264EndOfStreamMarkers;
use AsyncAws\MediaConvert\Enum\H264EntropyEncoding;
use AsyncAws\MediaConvert\Enum\H264FieldEncoding;
use AsyncAws\MediaConvert\Enum\H264FlickerAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264FramerateControl;
use AsyncAws\MediaConvert\Enum\H264FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\H264GopBReference;
use AsyncAws\MediaConvert\Enum\H264GopSizeUnits;
use AsyncAws\MediaConvert\Enum\H264InterlaceMode;
use AsyncAws\MediaConvert\Enum\H264ParControl;
use AsyncAws\MediaConvert\Enum\H264QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\H264RateControlMode;
use AsyncAws\MediaConvert\Enum\H264RepeatPps;
use AsyncAws\MediaConvert\Enum\H264SaliencyAwareEncoding;
use AsyncAws\MediaConvert\Enum\H264ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\H264SceneChangeDetect;
use AsyncAws\MediaConvert\Enum\H264SlowPal;
use AsyncAws\MediaConvert\Enum\H264SpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264Syntax;
use AsyncAws\MediaConvert\Enum\H264Telecine;
use AsyncAws\MediaConvert\Enum\H264TemporalAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264UnregisteredSeiTimecode;
use AsyncAws\MediaConvert\Enum\H264WriteMp4PackagingType;

/**
 * Required when you set Codec to the value H_264.
 */
final class H264Settings
{
    /**
     * Keep the default value, Auto, for this setting to have MediaConvert automatically apply the best types of
     * quantization for your video content. When you want to apply your quantization settings manually, you must set
     * H264AdaptiveQuantization to a value other than Auto. Use this setting to specify the strength of any adaptive
     * quantization filters that you enable. If you don't want MediaConvert to do any adaptive quantization in this
     * transcode, set Adaptive quantization to Off. Related settings: The value that you choose here applies to the
     * following settings: H264FlickerAdaptiveQuantization, H264SpatialAdaptiveQuantization, and
     * H264TemporalAdaptiveQuantization.
     *
     * @var H264AdaptiveQuantization::*|null
     */
    private $adaptiveQuantization;

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
     * Specify an H.264 level that is consistent with your output video settings. If you aren't sure what level to specify,
     * choose Auto.
     *
     * @var H264CodecLevel::*|null
     */
    private $codecLevel;

    /**
     * H.264 Profile. High 4:2:2 and 10-bit profiles are only available with the AVC-I License.
     *
     * @var H264CodecProfile::*|null
     */
    private $codecProfile;

    /**
     * Specify whether to allow the number of B-frames in your output GOP structure to vary or not depending on your input
     * video content. To improve the subjective video quality of your output that has high-motion content: Leave blank or
     * keep the default value Adaptive. MediaConvert will use fewer B-frames for high-motion video content than low-motion
     * content. The maximum number of B- frames is limited by the value that you choose for B-frames between reference
     * frames. To use the same number B-frames for all types of content: Choose Static.
     *
     * @var H264DynamicSubGop::*|null
     */
    private $dynamicSubGop;

    /**
     * Optionally include or suppress markers at the end of your output that signal the end of the video stream. To include
     * end of stream markers: Leave blank or keep the default value, Include. To not include end of stream markers: Choose
     * Suppress. This is useful when your output will be inserted into another stream.
     *
     * @var H264EndOfStreamMarkers::*|null
     */
    private $endOfStreamMarkers;

    /**
     * Entropy encoding mode. Use CABAC (must be in Main or High profile) or CAVLC.
     *
     * @var H264EntropyEncoding::*|null
     */
    private $entropyEncoding;

    /**
     * The video encoding method for your MPEG-4 AVC output. Keep the default value, PAFF, to have MediaConvert use PAFF
     * encoding for interlaced outputs. Choose Force field to disable PAFF encoding and create separate interlaced fields.
     * Choose MBAFF to disable PAFF and have MediaConvert use MBAFF encoding for interlaced outputs.
     *
     * @var H264FieldEncoding::*|null
     */
    private $fieldEncoding;

    /**
     * Only use this setting when you change the default value, AUTO, for the setting H264AdaptiveQuantization. When you
     * keep all defaults, excluding H264AdaptiveQuantization and all other adaptive quantization from your JSON job
     * specification, MediaConvert automatically applies the best types of quantization for your video content. When you set
     * H264AdaptiveQuantization to a value other than AUTO, the default value for H264FlickerAdaptiveQuantization is
     * Disabled. Change this value to Enabled to reduce I-frame pop. I-frame pop appears as a visual flicker that can arise
     * when the encoder saves bits by copying some macroblocks many times from frame to frame, and then refreshes them at
     * the I-frame. When you enable this setting, the encoder updates these macroblocks slightly more often to smooth out
     * the flicker. To manually enable or disable H264FlickerAdaptiveQuantization, you must set Adaptive quantization to a
     * value other than AUTO.
     *
     * @var H264FlickerAdaptiveQuantization::*|null
     */
    private $flickerAdaptiveQuantization;

    /**
     * If you are using the console, use the Framerate setting to specify the frame rate for this output. If you want to
     * keep the same frame rate as the input video, choose Follow source. If you want to do frame rate conversion, choose a
     * frame rate from the dropdown list or choose Custom. The framerates shown in the dropdown list are decimal
     * approximations of fractions. If you choose Custom, specify your frame rate as a fraction.
     *
     * @var H264FramerateControl::*|null
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
     * @var H264FramerateConversionAlgorithm::*|null
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
     * @var H264GopBReference::*|null
     */
    private $gopBreference;

    /**
     * Specify the relative frequency of open to closed GOPs in this output. For example, if you want to allow four open
     * GOPs and then require a closed GOP, set this value to 5. We recommend that you have the transcoder automatically
     * choose this value for you based on characteristics of your input video. In the console, do this by keeping the
     * default empty value. If you do explicitly specify a value, for segmented outputs, don't set this value to 0.
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
     * @var H264GopSizeUnits::*|null
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
     * @var H264InterlaceMode::*|null
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
     * Follow source, uses the PAR from your input video for your output. To specify a different PAR in the console, choose
     * any value other than Follow source. When you choose SPECIFIED for this setting, you must also specify values for the
     * parNumerator and parDenominator settings.
     *
     * @var H264ParControl::*|null
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
     * The Quality tuning level you choose represents a trade-off between the encoding speed of your job and the output
     * video quality. For the fastest encoding speed at the cost of video quality: Choose Single pass. For a good balance
     * between encoding speed and video quality: Leave blank or keep the default value Single pass HQ. For the best video
     * quality, at the cost of encoding speed: Choose Multi pass HQ. MediaConvert performs an analysis pass on your input
     * followed by an encoding pass. Outputs that use this feature incur pro-tier pricing.
     *
     * @var H264QualityTuningLevel::*|null
     */
    private $qualityTuningLevel;

    /**
     * Settings for quality-defined variable bitrate encoding with the H.265 codec. Use these settings only when you set
     * QVBR for Rate control mode.
     *
     * @var H264QvbrSettings|null
     */
    private $qvbrSettings;

    /**
     * Use this setting to specify whether this output has a variable bitrate (VBR), constant bitrate (CBR) or
     * quality-defined variable bitrate (QVBR).
     *
     * @var H264RateControlMode::*|null
     */
    private $rateControlMode;

    /**
     * Places a PPS header on each encoded picture, even if repeated.
     *
     * @var H264RepeatPps::*|null
     */
    private $repeatPps;

    /**
     * Specify whether to apply Saliency aware encoding to your output. Use to improve the perceptual video quality of your
     * output by allocating more encoding bits to the prominent or noticeable parts of your content. To apply saliency aware
     * encoding, when possible: We recommend that you choose Preferred. The effects of Saliency aware encoding are best seen
     * in lower bitrate outputs. When you choose Preferred, note that Saliency aware encoding will only apply to outputs
     * that are 720p or higher in resolution. To not apply saliency aware encoding, prioritizing encoding speed over
     * perceptual video quality: Choose Disabled.
     *
     * @var H264SaliencyAwareEncoding::*|null
     */
    private $saliencyAwareEncoding;

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
     * @var H264ScanTypeConversionMode::*|null
     */
    private $scanTypeConversionMode;

    /**
     * Enable this setting to insert I-frames at scene changes that the service automatically detects. This improves video
     * quality and is enabled by default. If this output uses QVBR, choose Transition detection for further video quality
     * improvement. For more information about QVBR, see https://docs.aws.amazon.com/console/mediaconvert/cbr-vbr-qvbr.
     *
     * @var H264SceneChangeDetect::*|null
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
     * @var H264SlowPal::*|null
     */
    private $slowPal;

    /**
     * Ignore this setting unless you need to comply with a specification that requires a specific value. If you don't have
     * a specification requirement, we recommend that you adjust the softness of your output by using a lower value for the
     * setting Sharpness or by enabling a noise reducer filter. The Softness setting specifies the quantization matrices
     * that the encoder uses. Keep the default value, 0, for flat quantization. Choose the value 1 or 16 to use the default
     * JVT softening quantization matricies from the H.264 specification. Choose a value from 17 to 128 to use planar
     * interpolation. Increasing values from 17 to 128 result in increasing reduction of high-frequency data. The value 128
     * results in the softest video.
     *
     * @var int|null
     */
    private $softness;

    /**
     * Only use this setting when you change the default value, Auto, for the setting H264AdaptiveQuantization. When you
     * keep all defaults, excluding H264AdaptiveQuantization and all other adaptive quantization from your JSON job
     * specification, MediaConvert automatically applies the best types of quantization for your video content. When you set
     * H264AdaptiveQuantization to a value other than AUTO, the default value for H264SpatialAdaptiveQuantization is
     * Enabled. Keep this default value to adjust quantization within each frame based on spatial variation of content
     * complexity. When you enable this feature, the encoder uses fewer bits on areas that can sustain more distortion with
     * no noticeable visual degradation and uses more bits on areas where any small distortion will be noticeable. For
     * example, complex textured blocks are encoded with fewer bits and smooth textured blocks are encoded with more bits.
     * Enabling this feature will almost always improve your video quality. Note, though, that this feature doesn't take
     * into account where the viewer's attention is likely to be. If viewers are likely to be focusing their attention on a
     * part of the screen with a lot of complex texture, you might choose to set H264SpatialAdaptiveQuantization to
     * Disabled. Related setting: When you enable spatial adaptive quantization, set the value for Adaptive quantization
     * depending on your content. For homogeneous content, such as cartoons and video games, set it to Low. For content with
     * a wider variety of textures, set it to High or Higher. To manually enable or disable H264SpatialAdaptiveQuantization,
     * you must set Adaptive quantization to a value other than AUTO.
     *
     * @var H264SpatialAdaptiveQuantization::*|null
     */
    private $spatialAdaptiveQuantization;

    /**
     * Produces a bitstream compliant with SMPTE RP-2027.
     *
     * @var H264Syntax::*|null
     */
    private $syntax;

    /**
     * When you do frame rate conversion from 23.976 frames per second (fps) to 29.97 fps, and your output scan type is
     * interlaced, you can optionally enable hard or soft telecine to create a smoother picture. Hard telecine produces a
     * 29.97i output. Soft telecine produces an output with a 23.976 output that signals to the video player device to do
     * the conversion during play back. When you keep the default value, None, MediaConvert does a standard frame rate
     * conversion to 29.97 without doing anything with the field polarity to create a smoother picture.
     *
     * @var H264Telecine::*|null
     */
    private $telecine;

    /**
     * Only use this setting when you change the default value, AUTO, for the setting H264AdaptiveQuantization. When you
     * keep all defaults, excluding H264AdaptiveQuantization and all other adaptive quantization from your JSON job
     * specification, MediaConvert automatically applies the best types of quantization for your video content. When you set
     * H264AdaptiveQuantization to a value other than AUTO, the default value for H264TemporalAdaptiveQuantization is
     * Enabled. Keep this default value to adjust quantization within each frame based on temporal variation of content
     * complexity. When you enable this feature, the encoder uses fewer bits on areas of the frame that aren't moving and
     * uses more bits on complex objects with sharp edges that move a lot. For example, this feature improves the
     * readability of text tickers on newscasts and scoreboards on sports matches. Enabling this feature will almost always
     * improve your video quality. Note, though, that this feature doesn't take into account where the viewer's attention is
     * likely to be. If viewers are likely to be focusing their attention on a part of the screen that doesn't have moving
     * objects with sharp edges, such as sports athletes' faces, you might choose to set H264TemporalAdaptiveQuantization to
     * Disabled. Related setting: When you enable temporal quantization, adjust the strength of the filter with the setting
     * Adaptive quantization. To manually enable or disable H264TemporalAdaptiveQuantization, you must set Adaptive
     * quantization to a value other than AUTO.
     *
     * @var H264TemporalAdaptiveQuantization::*|null
     */
    private $temporalAdaptiveQuantization;

    /**
     * Inserts timecode for each frame as 4 bytes of an unregistered SEI message.
     *
     * @var H264UnregisteredSeiTimecode::*|null
     */
    private $unregisteredSeiTimecode;

    /**
     * Specify how SPS and PPS NAL units are written in your output MP4 container, according to ISO/IEC 14496-15. If the
     * location of these parameters doesn't matter in your workflow: Keep the default value, AVC1. MediaConvert writes SPS
     * and PPS NAL units in the sample description ('stsd') box (but not into samples directly). To write SPS and PPS NAL
     * units directly into samples (but not in the 'stsd' box): Choose AVC3. When you do, note that your output might not
     * play properly with some downstream systems or players.
     *
     * @var H264WriteMp4PackagingType::*|null
     */
    private $writeMp4PackagingType;

    /**
     * @param array{
     *   AdaptiveQuantization?: null|H264AdaptiveQuantization::*,
     *   BandwidthReductionFilter?: null|BandwidthReductionFilter|array,
     *   Bitrate?: null|int,
     *   CodecLevel?: null|H264CodecLevel::*,
     *   CodecProfile?: null|H264CodecProfile::*,
     *   DynamicSubGop?: null|H264DynamicSubGop::*,
     *   EndOfStreamMarkers?: null|H264EndOfStreamMarkers::*,
     *   EntropyEncoding?: null|H264EntropyEncoding::*,
     *   FieldEncoding?: null|H264FieldEncoding::*,
     *   FlickerAdaptiveQuantization?: null|H264FlickerAdaptiveQuantization::*,
     *   FramerateControl?: null|H264FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|H264FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopBReference?: null|H264GopBReference::*,
     *   GopClosedCadence?: null|int,
     *   GopSize?: null|float,
     *   GopSizeUnits?: null|H264GopSizeUnits::*,
     *   HrdBufferFinalFillPercentage?: null|int,
     *   HrdBufferInitialFillPercentage?: null|int,
     *   HrdBufferSize?: null|int,
     *   InterlaceMode?: null|H264InterlaceMode::*,
     *   MaxBitrate?: null|int,
     *   MinIInterval?: null|int,
     *   NumberBFramesBetweenReferenceFrames?: null|int,
     *   NumberReferenceFrames?: null|int,
     *   ParControl?: null|H264ParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
     *   QualityTuningLevel?: null|H264QualityTuningLevel::*,
     *   QvbrSettings?: null|H264QvbrSettings|array,
     *   RateControlMode?: null|H264RateControlMode::*,
     *   RepeatPps?: null|H264RepeatPps::*,
     *   SaliencyAwareEncoding?: null|H264SaliencyAwareEncoding::*,
     *   ScanTypeConversionMode?: null|H264ScanTypeConversionMode::*,
     *   SceneChangeDetect?: null|H264SceneChangeDetect::*,
     *   Slices?: null|int,
     *   SlowPal?: null|H264SlowPal::*,
     *   Softness?: null|int,
     *   SpatialAdaptiveQuantization?: null|H264SpatialAdaptiveQuantization::*,
     *   Syntax?: null|H264Syntax::*,
     *   Telecine?: null|H264Telecine::*,
     *   TemporalAdaptiveQuantization?: null|H264TemporalAdaptiveQuantization::*,
     *   UnregisteredSeiTimecode?: null|H264UnregisteredSeiTimecode::*,
     *   WriteMp4PackagingType?: null|H264WriteMp4PackagingType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->adaptiveQuantization = $input['AdaptiveQuantization'] ?? null;
        $this->bandwidthReductionFilter = isset($input['BandwidthReductionFilter']) ? BandwidthReductionFilter::create($input['BandwidthReductionFilter']) : null;
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->codecLevel = $input['CodecLevel'] ?? null;
        $this->codecProfile = $input['CodecProfile'] ?? null;
        $this->dynamicSubGop = $input['DynamicSubGop'] ?? null;
        $this->endOfStreamMarkers = $input['EndOfStreamMarkers'] ?? null;
        $this->entropyEncoding = $input['EntropyEncoding'] ?? null;
        $this->fieldEncoding = $input['FieldEncoding'] ?? null;
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
        $this->qvbrSettings = isset($input['QvbrSettings']) ? H264QvbrSettings::create($input['QvbrSettings']) : null;
        $this->rateControlMode = $input['RateControlMode'] ?? null;
        $this->repeatPps = $input['RepeatPps'] ?? null;
        $this->saliencyAwareEncoding = $input['SaliencyAwareEncoding'] ?? null;
        $this->scanTypeConversionMode = $input['ScanTypeConversionMode'] ?? null;
        $this->sceneChangeDetect = $input['SceneChangeDetect'] ?? null;
        $this->slices = $input['Slices'] ?? null;
        $this->slowPal = $input['SlowPal'] ?? null;
        $this->softness = $input['Softness'] ?? null;
        $this->spatialAdaptiveQuantization = $input['SpatialAdaptiveQuantization'] ?? null;
        $this->syntax = $input['Syntax'] ?? null;
        $this->telecine = $input['Telecine'] ?? null;
        $this->temporalAdaptiveQuantization = $input['TemporalAdaptiveQuantization'] ?? null;
        $this->unregisteredSeiTimecode = $input['UnregisteredSeiTimecode'] ?? null;
        $this->writeMp4PackagingType = $input['WriteMp4PackagingType'] ?? null;
    }

    /**
     * @param array{
     *   AdaptiveQuantization?: null|H264AdaptiveQuantization::*,
     *   BandwidthReductionFilter?: null|BandwidthReductionFilter|array,
     *   Bitrate?: null|int,
     *   CodecLevel?: null|H264CodecLevel::*,
     *   CodecProfile?: null|H264CodecProfile::*,
     *   DynamicSubGop?: null|H264DynamicSubGop::*,
     *   EndOfStreamMarkers?: null|H264EndOfStreamMarkers::*,
     *   EntropyEncoding?: null|H264EntropyEncoding::*,
     *   FieldEncoding?: null|H264FieldEncoding::*,
     *   FlickerAdaptiveQuantization?: null|H264FlickerAdaptiveQuantization::*,
     *   FramerateControl?: null|H264FramerateControl::*,
     *   FramerateConversionAlgorithm?: null|H264FramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   GopBReference?: null|H264GopBReference::*,
     *   GopClosedCadence?: null|int,
     *   GopSize?: null|float,
     *   GopSizeUnits?: null|H264GopSizeUnits::*,
     *   HrdBufferFinalFillPercentage?: null|int,
     *   HrdBufferInitialFillPercentage?: null|int,
     *   HrdBufferSize?: null|int,
     *   InterlaceMode?: null|H264InterlaceMode::*,
     *   MaxBitrate?: null|int,
     *   MinIInterval?: null|int,
     *   NumberBFramesBetweenReferenceFrames?: null|int,
     *   NumberReferenceFrames?: null|int,
     *   ParControl?: null|H264ParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
     *   QualityTuningLevel?: null|H264QualityTuningLevel::*,
     *   QvbrSettings?: null|H264QvbrSettings|array,
     *   RateControlMode?: null|H264RateControlMode::*,
     *   RepeatPps?: null|H264RepeatPps::*,
     *   SaliencyAwareEncoding?: null|H264SaliencyAwareEncoding::*,
     *   ScanTypeConversionMode?: null|H264ScanTypeConversionMode::*,
     *   SceneChangeDetect?: null|H264SceneChangeDetect::*,
     *   Slices?: null|int,
     *   SlowPal?: null|H264SlowPal::*,
     *   Softness?: null|int,
     *   SpatialAdaptiveQuantization?: null|H264SpatialAdaptiveQuantization::*,
     *   Syntax?: null|H264Syntax::*,
     *   Telecine?: null|H264Telecine::*,
     *   TemporalAdaptiveQuantization?: null|H264TemporalAdaptiveQuantization::*,
     *   UnregisteredSeiTimecode?: null|H264UnregisteredSeiTimecode::*,
     *   WriteMp4PackagingType?: null|H264WriteMp4PackagingType::*,
     * }|H264Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return H264AdaptiveQuantization::*|null
     */
    public function getAdaptiveQuantization(): ?string
    {
        return $this->adaptiveQuantization;
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
     * @return H264CodecLevel::*|null
     */
    public function getCodecLevel(): ?string
    {
        return $this->codecLevel;
    }

    /**
     * @return H264CodecProfile::*|null
     */
    public function getCodecProfile(): ?string
    {
        return $this->codecProfile;
    }

    /**
     * @return H264DynamicSubGop::*|null
     */
    public function getDynamicSubGop(): ?string
    {
        return $this->dynamicSubGop;
    }

    /**
     * @return H264EndOfStreamMarkers::*|null
     */
    public function getEndOfStreamMarkers(): ?string
    {
        return $this->endOfStreamMarkers;
    }

    /**
     * @return H264EntropyEncoding::*|null
     */
    public function getEntropyEncoding(): ?string
    {
        return $this->entropyEncoding;
    }

    /**
     * @return H264FieldEncoding::*|null
     */
    public function getFieldEncoding(): ?string
    {
        return $this->fieldEncoding;
    }

    /**
     * @return H264FlickerAdaptiveQuantization::*|null
     */
    public function getFlickerAdaptiveQuantization(): ?string
    {
        return $this->flickerAdaptiveQuantization;
    }

    /**
     * @return H264FramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return H264FramerateConversionAlgorithm::*|null
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
     * @return H264GopBReference::*|null
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
     * @return H264GopSizeUnits::*|null
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
     * @return H264InterlaceMode::*|null
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
     * @return H264ParControl::*|null
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
     * @return H264QualityTuningLevel::*|null
     */
    public function getQualityTuningLevel(): ?string
    {
        return $this->qualityTuningLevel;
    }

    public function getQvbrSettings(): ?H264QvbrSettings
    {
        return $this->qvbrSettings;
    }

    /**
     * @return H264RateControlMode::*|null
     */
    public function getRateControlMode(): ?string
    {
        return $this->rateControlMode;
    }

    /**
     * @return H264RepeatPps::*|null
     */
    public function getRepeatPps(): ?string
    {
        return $this->repeatPps;
    }

    /**
     * @return H264SaliencyAwareEncoding::*|null
     */
    public function getSaliencyAwareEncoding(): ?string
    {
        return $this->saliencyAwareEncoding;
    }

    /**
     * @return H264ScanTypeConversionMode::*|null
     */
    public function getScanTypeConversionMode(): ?string
    {
        return $this->scanTypeConversionMode;
    }

    /**
     * @return H264SceneChangeDetect::*|null
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
     * @return H264SlowPal::*|null
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
     * @return H264SpatialAdaptiveQuantization::*|null
     */
    public function getSpatialAdaptiveQuantization(): ?string
    {
        return $this->spatialAdaptiveQuantization;
    }

    /**
     * @return H264Syntax::*|null
     */
    public function getSyntax(): ?string
    {
        return $this->syntax;
    }

    /**
     * @return H264Telecine::*|null
     */
    public function getTelecine(): ?string
    {
        return $this->telecine;
    }

    /**
     * @return H264TemporalAdaptiveQuantization::*|null
     */
    public function getTemporalAdaptiveQuantization(): ?string
    {
        return $this->temporalAdaptiveQuantization;
    }

    /**
     * @return H264UnregisteredSeiTimecode::*|null
     */
    public function getUnregisteredSeiTimecode(): ?string
    {
        return $this->unregisteredSeiTimecode;
    }

    /**
     * @return H264WriteMp4PackagingType::*|null
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
            if (!H264AdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "adaptiveQuantization" for "%s". The value "%s" is not a valid "H264AdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['adaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->bandwidthReductionFilter) {
            $payload['bandwidthReductionFilter'] = $v->requestBody();
        }
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->codecLevel) {
            if (!H264CodecLevel::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codecLevel" for "%s". The value "%s" is not a valid "H264CodecLevel".', __CLASS__, $v));
            }
            $payload['codecLevel'] = $v;
        }
        if (null !== $v = $this->codecProfile) {
            if (!H264CodecProfile::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codecProfile" for "%s". The value "%s" is not a valid "H264CodecProfile".', __CLASS__, $v));
            }
            $payload['codecProfile'] = $v;
        }
        if (null !== $v = $this->dynamicSubGop) {
            if (!H264DynamicSubGop::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicSubGop" for "%s". The value "%s" is not a valid "H264DynamicSubGop".', __CLASS__, $v));
            }
            $payload['dynamicSubGop'] = $v;
        }
        if (null !== $v = $this->endOfStreamMarkers) {
            if (!H264EndOfStreamMarkers::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "endOfStreamMarkers" for "%s". The value "%s" is not a valid "H264EndOfStreamMarkers".', __CLASS__, $v));
            }
            $payload['endOfStreamMarkers'] = $v;
        }
        if (null !== $v = $this->entropyEncoding) {
            if (!H264EntropyEncoding::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "entropyEncoding" for "%s". The value "%s" is not a valid "H264EntropyEncoding".', __CLASS__, $v));
            }
            $payload['entropyEncoding'] = $v;
        }
        if (null !== $v = $this->fieldEncoding) {
            if (!H264FieldEncoding::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "fieldEncoding" for "%s". The value "%s" is not a valid "H264FieldEncoding".', __CLASS__, $v));
            }
            $payload['fieldEncoding'] = $v;
        }
        if (null !== $v = $this->flickerAdaptiveQuantization) {
            if (!H264FlickerAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "flickerAdaptiveQuantization" for "%s". The value "%s" is not a valid "H264FlickerAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['flickerAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->framerateControl) {
            if (!H264FramerateControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "H264FramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!H264FramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "H264FramerateConversionAlgorithm".', __CLASS__, $v));
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
            if (!H264GopBReference::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "gopBReference" for "%s". The value "%s" is not a valid "H264GopBReference".', __CLASS__, $v));
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
            if (!H264GopSizeUnits::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "gopSizeUnits" for "%s". The value "%s" is not a valid "H264GopSizeUnits".', __CLASS__, $v));
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
            if (!H264InterlaceMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "H264InterlaceMode".', __CLASS__, $v));
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
            if (!H264ParControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "parControl" for "%s". The value "%s" is not a valid "H264ParControl".', __CLASS__, $v));
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
            if (!H264QualityTuningLevel::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "qualityTuningLevel" for "%s". The value "%s" is not a valid "H264QualityTuningLevel".', __CLASS__, $v));
            }
            $payload['qualityTuningLevel'] = $v;
        }
        if (null !== $v = $this->qvbrSettings) {
            $payload['qvbrSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->rateControlMode) {
            if (!H264RateControlMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "rateControlMode" for "%s". The value "%s" is not a valid "H264RateControlMode".', __CLASS__, $v));
            }
            $payload['rateControlMode'] = $v;
        }
        if (null !== $v = $this->repeatPps) {
            if (!H264RepeatPps::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "repeatPps" for "%s". The value "%s" is not a valid "H264RepeatPps".', __CLASS__, $v));
            }
            $payload['repeatPps'] = $v;
        }
        if (null !== $v = $this->saliencyAwareEncoding) {
            if (!H264SaliencyAwareEncoding::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "saliencyAwareEncoding" for "%s". The value "%s" is not a valid "H264SaliencyAwareEncoding".', __CLASS__, $v));
            }
            $payload['saliencyAwareEncoding'] = $v;
        }
        if (null !== $v = $this->scanTypeConversionMode) {
            if (!H264ScanTypeConversionMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scanTypeConversionMode" for "%s". The value "%s" is not a valid "H264ScanTypeConversionMode".', __CLASS__, $v));
            }
            $payload['scanTypeConversionMode'] = $v;
        }
        if (null !== $v = $this->sceneChangeDetect) {
            if (!H264SceneChangeDetect::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "sceneChangeDetect" for "%s". The value "%s" is not a valid "H264SceneChangeDetect".', __CLASS__, $v));
            }
            $payload['sceneChangeDetect'] = $v;
        }
        if (null !== $v = $this->slices) {
            $payload['slices'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!H264SlowPal::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "H264SlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->softness) {
            $payload['softness'] = $v;
        }
        if (null !== $v = $this->spatialAdaptiveQuantization) {
            if (!H264SpatialAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "spatialAdaptiveQuantization" for "%s". The value "%s" is not a valid "H264SpatialAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['spatialAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->syntax) {
            if (!H264Syntax::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "syntax" for "%s". The value "%s" is not a valid "H264Syntax".', __CLASS__, $v));
            }
            $payload['syntax'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!H264Telecine::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "H264Telecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }
        if (null !== $v = $this->temporalAdaptiveQuantization) {
            if (!H264TemporalAdaptiveQuantization::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "temporalAdaptiveQuantization" for "%s". The value "%s" is not a valid "H264TemporalAdaptiveQuantization".', __CLASS__, $v));
            }
            $payload['temporalAdaptiveQuantization'] = $v;
        }
        if (null !== $v = $this->unregisteredSeiTimecode) {
            if (!H264UnregisteredSeiTimecode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "unregisteredSeiTimecode" for "%s". The value "%s" is not a valid "H264UnregisteredSeiTimecode".', __CLASS__, $v));
            }
            $payload['unregisteredSeiTimecode'] = $v;
        }
        if (null !== $v = $this->writeMp4PackagingType) {
            if (!H264WriteMp4PackagingType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "writeMp4PackagingType" for "%s". The value "%s" is not a valid "H264WriteMp4PackagingType".', __CLASS__, $v));
            }
            $payload['writeMp4PackagingType'] = $v;
        }

        return $payload;
    }
}
