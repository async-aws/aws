<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the XAVC Intra HD (CBG) Class to set the bitrate of your output. Outputs of the same class have similar image
 * quality over the operating points that are valid for that class.
 */
final class XavcHdIntraCbgProfileClass
{
    public const CLASS_100 = 'CLASS_100';
    public const CLASS_200 = 'CLASS_200';
    public const CLASS_50 = 'CLASS_50';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CLASS_100 => true,
            self::CLASS_200 => true,
            self::CLASS_50 => true,
        ][$value]);
    }
}
