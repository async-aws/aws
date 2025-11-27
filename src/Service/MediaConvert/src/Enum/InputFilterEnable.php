<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether to apply input filtering to improve the video quality of your input. To apply filtering depending on
 * your input type and quality: Choose Auto. To apply no filtering: Choose Disable. To apply filtering regardless of
 * your input type and quality: Choose Force. When you do, you must also specify a value for Filter strength.
 */
final class InputFilterEnable
{
    public const AUTO = 'AUTO';
    public const DISABLE = 'DISABLE';
    public const FORCE = 'FORCE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::DISABLE => true,
            self::FORCE => true,
        ][$value]);
    }
}
