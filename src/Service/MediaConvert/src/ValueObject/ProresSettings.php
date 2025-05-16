<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\FrameMetricType;
use AsyncAws\MediaConvert\Enum\ProresChromaSampling;
use AsyncAws\MediaConvert\Enum\ProresCodecProfile;
use AsyncAws\MediaConvert\Enum\ProresFramerateControl;
use AsyncAws\MediaConvert\Enum\ProresFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\ProresInterlaceMode;
use AsyncAws\MediaConvert\Enum\ProresParControl;
use AsyncAws\MediaConvert\Enum\ProresScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\ProresSlowPal;
use AsyncAws\MediaConvert\Enum\ProresTelecine;

/**
 * Required when you set Codec to the value PRORES.
 */
final class ProresSettings
{
    /**
     * This setting applies only to ProRes 4444 and ProRes 4444 XQ outputs that you create from inputs that use 4:4:4 chroma
     * sampling. Set Preserve 4:4:4 sampling to allow outputs to also use 4:4:4 chroma sampling. You must specify a value
     * for this setting when your output codec profile supports 4:4:4 chroma sampling. Related Settings: For Apple ProRes
     * outputs with 4:4:4 chroma sampling: Choose Preserve 4:4:4 sampling. Use when your input has 4:4:4 chroma sampling and
     * your output codec Profile is Apple ProRes 4444 or 4444 XQ. Note that when you choose Preserve 4:4:4 sampling, you
     * cannot include any of the following Preprocessors: Dolby Vision, HDR10+, or Noise reducer.
     *
     * @var ProresChromaSampling::*|null
     */
    private $chromaSampling;

    /**
     * Use Profile to specify the type of Apple ProRes codec to use for this output.
     *
     * @var ProresCodecProfile::*|null
     */
    private $codecProfile;

    /**
     * If you are using the console, use the Framerate setting to specify the frame rate for this output. If you want to
     * keep the same frame rate as the input video, choose Follow source. If you want to do frame rate conversion, choose a
     * frame rate from the dropdown list or choose Custom. The framerates shown in the dropdown list are decimal
     * approximations of fractions. If you choose Custom, specify your frame rate as a fraction.
     *
     * @var ProresFramerateControl::*|null
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
     * @var ProresFramerateConversionAlgorithm::*|null
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
     * @var ProresInterlaceMode::*|null
     */
    private $interlaceMode;

    /**
     * Optional. Specify how the service determines the pixel aspect ratio (PAR) for this output. The default behavior,
     * Follow source, uses the PAR from your input video for your output. To specify a different PAR, choose any value other
     * than Follow source. When you choose SPECIFIED for this setting, you must also specify values for the parNumerator and
     * parDenominator settings.
     *
     * @var ProresParControl::*|null
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
     * @var ProresScanTypeConversionMode::*|null
     */
    private $scanTypeConversionMode;

    /**
     * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
     * 25 fps output. When you enable slow PAL, MediaConvert relabels the video frames to 25 fps and resamples your audio to
     * keep it synchronized with the video. Note that enabling this setting will slightly reduce the duration of your video.
     * Required settings: You must also set Framerate to 25.
     *
     * @var ProresSlowPal::*|null
     */
    private $slowPal;

    /**
     * When you do frame rate conversion from 23.976 frames per second (fps) to 29.97 fps, and your output scan type is
     * interlaced, you can optionally enable hard telecine to create a smoother picture. When you keep the default value,
     * None, MediaConvert does a standard frame rate conversion to 29.97 without doing anything with the field polarity to
     * create a smoother picture.
     *
     * @var ProresTelecine::*|null
     */
    private $telecine;

    /**
     * @param array{
     *   ChromaSampling?: null|ProresChromaSampling::*,
     *   CodecProfile?: null|ProresCodecProfile::*,
     *   FramerateControl?: null|ProresFramerateControl::*,
     *   FramerateConversionAlgorithm?: null|ProresFramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   InterlaceMode?: null|ProresInterlaceMode::*,
     *   ParControl?: null|ProresParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
     *   ScanTypeConversionMode?: null|ProresScanTypeConversionMode::*,
     *   SlowPal?: null|ProresSlowPal::*,
     *   Telecine?: null|ProresTelecine::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->chromaSampling = $input['ChromaSampling'] ?? null;
        $this->codecProfile = $input['CodecProfile'] ?? null;
        $this->framerateControl = $input['FramerateControl'] ?? null;
        $this->framerateConversionAlgorithm = $input['FramerateConversionAlgorithm'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->interlaceMode = $input['InterlaceMode'] ?? null;
        $this->parControl = $input['ParControl'] ?? null;
        $this->parDenominator = $input['ParDenominator'] ?? null;
        $this->parNumerator = $input['ParNumerator'] ?? null;
        $this->perFrameMetrics = $input['PerFrameMetrics'] ?? null;
        $this->scanTypeConversionMode = $input['ScanTypeConversionMode'] ?? null;
        $this->slowPal = $input['SlowPal'] ?? null;
        $this->telecine = $input['Telecine'] ?? null;
    }

    /**
     * @param array{
     *   ChromaSampling?: null|ProresChromaSampling::*,
     *   CodecProfile?: null|ProresCodecProfile::*,
     *   FramerateControl?: null|ProresFramerateControl::*,
     *   FramerateConversionAlgorithm?: null|ProresFramerateConversionAlgorithm::*,
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     *   InterlaceMode?: null|ProresInterlaceMode::*,
     *   ParControl?: null|ProresParControl::*,
     *   ParDenominator?: null|int,
     *   ParNumerator?: null|int,
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
     *   ScanTypeConversionMode?: null|ProresScanTypeConversionMode::*,
     *   SlowPal?: null|ProresSlowPal::*,
     *   Telecine?: null|ProresTelecine::*,
     * }|ProresSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ProresChromaSampling::*|null
     */
    public function getChromaSampling(): ?string
    {
        return $this->chromaSampling;
    }

    /**
     * @return ProresCodecProfile::*|null
     */
    public function getCodecProfile(): ?string
    {
        return $this->codecProfile;
    }

    /**
     * @return ProresFramerateControl::*|null
     */
    public function getFramerateControl(): ?string
    {
        return $this->framerateControl;
    }

    /**
     * @return ProresFramerateConversionAlgorithm::*|null
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
     * @return ProresInterlaceMode::*|null
     */
    public function getInterlaceMode(): ?string
    {
        return $this->interlaceMode;
    }

    /**
     * @return ProresParControl::*|null
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
     * @return ProresScanTypeConversionMode::*|null
     */
    public function getScanTypeConversionMode(): ?string
    {
        return $this->scanTypeConversionMode;
    }

    /**
     * @return ProresSlowPal::*|null
     */
    public function getSlowPal(): ?string
    {
        return $this->slowPal;
    }

    /**
     * @return ProresTelecine::*|null
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
        if (null !== $v = $this->chromaSampling) {
            if (!ProresChromaSampling::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "chromaSampling" for "%s". The value "%s" is not a valid "ProresChromaSampling".', __CLASS__, $v));
            }
            $payload['chromaSampling'] = $v;
        }
        if (null !== $v = $this->codecProfile) {
            if (!ProresCodecProfile::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codecProfile" for "%s". The value "%s" is not a valid "ProresCodecProfile".', __CLASS__, $v));
            }
            $payload['codecProfile'] = $v;
        }
        if (null !== $v = $this->framerateControl) {
            if (!ProresFramerateControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateControl" for "%s". The value "%s" is not a valid "ProresFramerateControl".', __CLASS__, $v));
            }
            $payload['framerateControl'] = $v;
        }
        if (null !== $v = $this->framerateConversionAlgorithm) {
            if (!ProresFramerateConversionAlgorithm::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "framerateConversionAlgorithm" for "%s". The value "%s" is not a valid "ProresFramerateConversionAlgorithm".', __CLASS__, $v));
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
            if (!ProresInterlaceMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "ProresInterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
        }
        if (null !== $v = $this->parControl) {
            if (!ProresParControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "parControl" for "%s". The value "%s" is not a valid "ProresParControl".', __CLASS__, $v));
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
        if (null !== $v = $this->scanTypeConversionMode) {
            if (!ProresScanTypeConversionMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scanTypeConversionMode" for "%s". The value "%s" is not a valid "ProresScanTypeConversionMode".', __CLASS__, $v));
            }
            $payload['scanTypeConversionMode'] = $v;
        }
        if (null !== $v = $this->slowPal) {
            if (!ProresSlowPal::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "slowPal" for "%s". The value "%s" is not a valid "ProresSlowPal".', __CLASS__, $v));
            }
            $payload['slowPal'] = $v;
        }
        if (null !== $v = $this->telecine) {
            if (!ProresTelecine::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "telecine" for "%s". The value "%s" is not a valid "ProresTelecine".', __CLASS__, $v));
            }
            $payload['telecine'] = $v;
        }

        return $payload;
    }
}
