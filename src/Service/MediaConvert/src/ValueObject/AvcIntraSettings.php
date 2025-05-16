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
use AsyncAws\MediaConvert\Enum\FrameMetricType;

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
     *
     * @var AvcIntraClass::*|null
     */
    private $avcIntraClass;

    /**
     * Optional when you set AVC-Intra class to Class 4K/2K. When you set AVC-Intra class to a different value, this object
     * isn't allowed.
     *
     * @var AvcIntraUhdSettings|null
     */
    private $avcIntraUhdSettings;

    /**
     * If you are using the console, use the Framerate setting to specify the frame rate for this output. If you want to
     * keep the same frame rate as the input video, choose Follow source. If you want to do frame rate conversion, choose a
     * frame rate from the dropdown list or choose Custom. The framerates shown in the dropdown list are decimal
     * approximations of fractions. If you choose Custom, specify your frame rate as a fraction.
     *
     * @var AvcIntraFramerateControl::*|null
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
     * @var AvcIntraFramerateConversionAlgorithm::*|null
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
     * Choose the scan line type for the output. Keep the default value, Progressive to create a progressive output,
     * regardless of the scan type of your input. Use Top field first or Bottom field first to create an output that's
     * interlaced with the same field polarity throughout. Use Follow, default top or Follow, default bottom to produce
     * outputs with the same field polarity as the source. For jobs that have multiple inputs, the output field polarity
     * might change over the course of the output. Follow behavior depends on the input scan type. If the source is
     * interlaced, the output will be interlaced with the same polarity as the source. If the source is progressive, the
     * output will be interlaced with top field bottom field first, depending on which of the Follow options you choose.
     *
     * @var AvcIntraInterlaceMode::*|null
     */
    private $interlaceMode;

    /**
     * Optionally choose one or more per frame metric reports to generate along with your output. You can use these metrics
     * to analyze your video output according to one or more commonly used image quality metrics. You can specify per frame
     * metrics for output groups or for individual outputs. When you do, MediaConvert writes a CSV (Comma-Separated Values)
     * file to your S3 output destination, named after the output name and metric type. For example: videofile_PSNR.csv Jobs
     * that generate per frame metrics will take longer to complete, depending on the resolution and complexity of your
     * output. For example, some 4K jobs might take up to twice as long to complete. Note that when analyzing the video
     * quality of your output, or when comparing the video quality of multiple different outputs, we generally also
     * recommend a detailed visual review in a controlled environment. You can choose from the following per frame metrics:
     * * PSNR: Peak Signal-to-Noise Ratio * SSIM: Structural Similarity Index Measure * MS_SSIM: Multi-Scale Similarity
     * Index Measure * PSNR_HVS: Peak Signal-to-Noise Ratio, Human Visual System * VMAF: Video Multi-Method Assessment
     * Fusion * QVBR: Quality-Defined Variable Bitrate. This option is only available when your output uses the QVBR rate
     * control mode.
     *
     * @var list<FrameMetricType::*>|null
     */
    private $perFrameMetrics;

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
     * @var AvcIntraScanTypeConversionMode::*|null
     */
    private $scanTypeConversionMode;

    /**
     * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
     * 25 fps output. When you enable slow PAL, MediaConvert relabels the video frames to 25 fps and resamples your audio to
     * keep it synchronized with the video. Note that enabling this setting will slightly reduce the duration of your video.
     * Required settings: You must also set Framerate to 25.
     *
     * @var AvcIntraSlowPal::*|null
     */
    private $slowPal;

    /**
     * When you do frame rate conversion from 23.976 frames per second (fps) to 29.97 fps, and your output scan type is
     * interlaced, you can optionally enable hard telecine to create a smoother picture. When you keep the default value,
     * None, MediaConvert does a standard frame rate conversion to 29.97 without doing anything with the field polarity to
     * create a smoother picture.
     *
     * @var AvcIntraTelecine::*|null
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
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
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
        $this->perFrameMetrics = $input['PerFrameMetrics'] ?? null;
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
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
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
     * @return list<FrameMetricType::*>
     */
    public function getPerFrameMetrics(): array
    {
        return $this->perFrameMetrics ?? [];
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
                throw new InvalidArgument(\sprintf('Invalid parameter "avcIntraClass" for "%s". The value "%s" is not a valid "AvcIntraClass".', __CLASS__, $v));
            }
            $payload['avcIntraClass'] = $v;
        }
        if (null !== $v = $this->avcIntraUhdSettings) {
            $payload['avcIntraUhdSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->framerateControl) {
            if (!AvcIntraFramerateControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "AvcIntraFramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!AvcIntraFramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "AvcIntraFramerateConversionAlgorithm".', __CLASS__, $v));
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
                throw new InvalidArgument(\sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "AvcIntraInterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
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
        if (null !== $v = $this->scanTypeConversionMode) {
            if (!AvcIntraScanTypeConversionMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scanTypeConversionMode" for "%s". The value "%s" is not a valid "AvcIntraScanTypeConversionMode".', __CLASS__, $v));
            }
            $payload['scanTypeConversionMode'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!AvcIntraSlowPal::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "AvcIntraSlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!AvcIntraTelecine::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "AvcIntraTelecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }

        return $payload;
    }
}
