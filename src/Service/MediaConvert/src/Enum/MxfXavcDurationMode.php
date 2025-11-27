<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To create an output that complies with the XAVC file format guidelines for interoperability, keep the default value,
 * Drop frames for compliance. To include all frames from your input in this output, keep the default setting, Allow any
 * duration. The number of frames that MediaConvert excludes when you set this to Drop frames for compliance depends on
 * the output frame rate and duration.
 */
final class MxfXavcDurationMode
{
    public const ALLOW_ANY_DURATION = 'ALLOW_ANY_DURATION';
    public const DROP_FRAMES_FOR_COMPLIANCE = 'DROP_FRAMES_FOR_COMPLIANCE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALLOW_ANY_DURATION => true,
            self::DROP_FRAMES_FOR_COMPLIANCE => true,
        ][$value]);
    }
}
