<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * * PSNR: Peak Signal-to-Noise Ratio * SSIM: Structural Similarity Index Measure * MS_SSIM: Multi-Scale Similarity
 * Index Measure * PSNR_HVS: Peak Signal-to-Noise Ratio, Human Visual System * VMAF: Video Multi-Method Assessment
 * Fusion * QVBR: Quality-Defined Variable Bitrate. This option is only available when your output uses the QVBR rate
 * control mode. * SHOT_CHANGE: Shot Changes.
 */
final class FrameMetricType
{
    public const MS_SSIM = 'MS_SSIM';
    public const PSNR = 'PSNR';
    public const PSNR_HVS = 'PSNR_HVS';
    public const QVBR = 'QVBR';
    public const SHOT_CHANGE = 'SHOT_CHANGE';
    public const SSIM = 'SSIM';
    public const VMAF = 'VMAF';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MS_SSIM => true,
            self::PSNR => true,
            self::PSNR_HVS => true,
            self::QVBR => true,
            self::SHOT_CHANGE => true,
            self::SSIM => true,
            self::VMAF => true,
        ][$value]);
    }
}
