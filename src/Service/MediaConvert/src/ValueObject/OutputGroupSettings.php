<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\FrameMetricType;
use AsyncAws\MediaConvert\Enum\OutputGroupType;

/**
 * Output Group settings, including type.
 */
final class OutputGroupSettings
{
    /**
     * Settings related to your CMAF output package. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/outputs-file-ABR.html.
     *
     * @var CmafGroupSettings|null
     */
    private $cmafGroupSettings;

    /**
     * Settings related to your DASH output package. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/outputs-file-ABR.html.
     *
     * @var DashIsoGroupSettings|null
     */
    private $dashIsoGroupSettings;

    /**
     * Settings related to your File output group. MediaConvert uses this group of settings to generate a single standalone
     * file, rather than a streaming package.
     *
     * @var FileGroupSettings|null
     */
    private $fileGroupSettings;

    /**
     * Settings related to your HLS output package. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/outputs-file-ABR.html.
     *
     * @var HlsGroupSettings|null
     */
    private $hlsGroupSettings;

    /**
     * Settings related to your Microsoft Smooth Streaming output package. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/outputs-file-ABR.html.
     *
     * @var MsSmoothGroupSettings|null
     */
    private $msSmoothGroupSettings;

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
     * Type of output group (File group, Apple HLS, DASH ISO, Microsoft Smooth Streaming, CMAF).
     *
     * @var OutputGroupType::*|null
     */
    private $type;

    /**
     * @param array{
     *   CmafGroupSettings?: null|CmafGroupSettings|array,
     *   DashIsoGroupSettings?: null|DashIsoGroupSettings|array,
     *   FileGroupSettings?: null|FileGroupSettings|array,
     *   HlsGroupSettings?: null|HlsGroupSettings|array,
     *   MsSmoothGroupSettings?: null|MsSmoothGroupSettings|array,
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
     *   Type?: null|OutputGroupType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cmafGroupSettings = isset($input['CmafGroupSettings']) ? CmafGroupSettings::create($input['CmafGroupSettings']) : null;
        $this->dashIsoGroupSettings = isset($input['DashIsoGroupSettings']) ? DashIsoGroupSettings::create($input['DashIsoGroupSettings']) : null;
        $this->fileGroupSettings = isset($input['FileGroupSettings']) ? FileGroupSettings::create($input['FileGroupSettings']) : null;
        $this->hlsGroupSettings = isset($input['HlsGroupSettings']) ? HlsGroupSettings::create($input['HlsGroupSettings']) : null;
        $this->msSmoothGroupSettings = isset($input['MsSmoothGroupSettings']) ? MsSmoothGroupSettings::create($input['MsSmoothGroupSettings']) : null;
        $this->perFrameMetrics = $input['PerFrameMetrics'] ?? null;
        $this->type = $input['Type'] ?? null;
    }

    /**
     * @param array{
     *   CmafGroupSettings?: null|CmafGroupSettings|array,
     *   DashIsoGroupSettings?: null|DashIsoGroupSettings|array,
     *   FileGroupSettings?: null|FileGroupSettings|array,
     *   HlsGroupSettings?: null|HlsGroupSettings|array,
     *   MsSmoothGroupSettings?: null|MsSmoothGroupSettings|array,
     *   PerFrameMetrics?: null|array<FrameMetricType::*>,
     *   Type?: null|OutputGroupType::*,
     * }|OutputGroupSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCmafGroupSettings(): ?CmafGroupSettings
    {
        return $this->cmafGroupSettings;
    }

    public function getDashIsoGroupSettings(): ?DashIsoGroupSettings
    {
        return $this->dashIsoGroupSettings;
    }

    public function getFileGroupSettings(): ?FileGroupSettings
    {
        return $this->fileGroupSettings;
    }

    public function getHlsGroupSettings(): ?HlsGroupSettings
    {
        return $this->hlsGroupSettings;
    }

    public function getMsSmoothGroupSettings(): ?MsSmoothGroupSettings
    {
        return $this->msSmoothGroupSettings;
    }

    /**
     * @return list<FrameMetricType::*>
     */
    public function getPerFrameMetrics(): array
    {
        return $this->perFrameMetrics ?? [];
    }

    /**
     * @return OutputGroupType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->cmafGroupSettings) {
            $payload['cmafGroupSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->dashIsoGroupSettings) {
            $payload['dashIsoGroupSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->fileGroupSettings) {
            $payload['fileGroupSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->hlsGroupSettings) {
            $payload['hlsGroupSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->msSmoothGroupSettings) {
            $payload['msSmoothGroupSettings'] = $v->requestBody();
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
        if (null !== $v = $this->type) {
            if (!OutputGroupType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "OutputGroupType".', __CLASS__, $v));
            }
            $payload['type'] = $v;
        }

        return $payload;
    }
}
