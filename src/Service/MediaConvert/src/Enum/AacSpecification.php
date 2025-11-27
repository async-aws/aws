<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use MPEG-2 AAC instead of MPEG-4 AAC audio for raw or MPEG-2 Transport Stream containers.
 */
final class AacSpecification
{
    public const MPEG2 = 'MPEG2';
    public const MPEG4 = 'MPEG4';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MPEG2 => true,
            self::MPEG4 => true,
        ][$value]);
    }
}
