<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the XAVC Intra 4k (CBG) Class to set the bitrate of your output. Outputs of the same class have similar image
 * quality over the operating points that are valid for that class.
 */
final class Xavc4kIntraCbgProfileClass
{
    public const CLASS_100 = 'CLASS_100';
    public const CLASS_300 = 'CLASS_300';
    public const CLASS_480 = 'CLASS_480';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CLASS_100 => true,
            self::CLASS_300 => true,
            self::CLASS_480 => true,
        ][$value]);
    }
}
