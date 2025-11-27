<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the XAVC profile for this output. For more information, see the Sony documentation at
 * https://www.xavc-info.org/. Note that MediaConvert doesn't support the interlaced video XAVC operating points for
 * XAVC_HD_INTRA_CBG. To create an interlaced XAVC output, choose the profile XAVC_HD.
 */
final class XavcProfile
{
    public const XAVC_4K = 'XAVC_4K';
    public const XAVC_4K_INTRA_CBG = 'XAVC_4K_INTRA_CBG';
    public const XAVC_4K_INTRA_VBR = 'XAVC_4K_INTRA_VBR';
    public const XAVC_HD = 'XAVC_HD';
    public const XAVC_HD_INTRA_CBG = 'XAVC_HD_INTRA_CBG';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::XAVC_4K => true,
            self::XAVC_4K_INTRA_CBG => true,
            self::XAVC_4K_INTRA_VBR => true,
            self::XAVC_HD => true,
            self::XAVC_HD_INTRA_CBG => true,
        ][$value]);
    }
}
