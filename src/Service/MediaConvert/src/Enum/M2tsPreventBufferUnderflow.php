<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether MediaConvert automatically attempts to prevent decoder buffer underflows in your transport stream
 * output. Use if you are seeing decoder buffer underflows in your output and are unable to increase your transport
 * stream's bitrate. For most workflows: We recommend that you keep the default value, Disabled. To prevent decoder
 * buffer underflows in your output, when possible: Choose Enabled. Note that if MediaConvert prevents a decoder buffer
 * underflow in your output, output video quality is reduced and your job will take longer to complete.
 */
final class M2tsPreventBufferUnderflow
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
