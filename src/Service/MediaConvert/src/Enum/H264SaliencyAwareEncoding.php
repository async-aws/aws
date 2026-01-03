<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether to apply Saliency aware encoding to your output. Use to improve the perceptual video quality of your
 * output by allocating more encoding bits to the prominent or noticeable parts of your content. To apply saliency aware
 * encoding, when possible: We recommend that you choose Preferred. The effects of Saliency aware encoding are best seen
 * in lower bitrate outputs. When you choose Preferred, note that Saliency aware encoding will only apply to outputs
 * that are 720p or higher in resolution. To not apply saliency aware encoding, prioritizing encoding speed over
 * perceptual video quality: Choose Disabled.
 */
final class H264SaliencyAwareEncoding
{
    public const DISABLED = 'DISABLED';
    public const PREFERRED = 'PREFERRED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::PREFERRED => true,
        ][$value]);
    }
}
