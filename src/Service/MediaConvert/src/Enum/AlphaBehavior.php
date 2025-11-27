<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless this input is a QuickTime animation with an alpha channel. Use this setting to create
 * separate Key and Fill outputs. In each output, specify which part of the input MediaConvert uses. Leave this setting
 * at the default value DISCARD to delete the alpha channel and preserve the video. Set it to REMAP_TO_LUMA to delete
 * the video and map the alpha channel to the luma channel of your outputs.
 */
final class AlphaBehavior
{
    public const DISCARD = 'DISCARD';
    public const REMAP_TO_LUMA = 'REMAP_TO_LUMA';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISCARD => true,
            self::REMAP_TO_LUMA => true,
        ][$value]);
    }
}
