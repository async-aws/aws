<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Min top rendition size to specify a minimum size for the highest resolution in your ABR stack. * The highest
 * resolution in your ABR stack will be equal to or greater than the value that you enter. For example: If you specify
 * 1280x720 the highest resolution in your ABR stack will be equal to or greater than 1280x720. * If you specify a value
 * for Max resolution, the value that you specify for Min top rendition size must be less than, or equal to, Max
 * resolution. Use Min bottom rendition size to specify a minimum size for the lowest resolution in your ABR stack. *
 * The lowest resolution in your ABR stack will be equal to or greater than the value that you enter. For example: If
 * you specify 640x360 the lowest resolution in your ABR stack will be equal to or greater than to 640x360. * If you
 * specify a Min top rendition size rule, the value that you specify for Min bottom rendition size must be less than, or
 * equal to, Min top rendition size. Use Force include renditions to specify one or more resolutions to include your ABR
 * stack. * (Recommended) To optimize automated ABR, specify as few resolutions as possible. * (Required) The number of
 * resolutions that you specify must be equal to, or less than, the Max renditions setting. * If you specify a Min top
 * rendition size rule, specify at least one resolution that is equal to, or greater than, Min top rendition size. * If
 * you specify a Min bottom rendition size rule, only specify resolutions that are equal to, or greater than, Min bottom
 * rendition size. * If you specify a Force include renditions rule, do not specify a separate rule for Allowed
 * renditions. * Note: The ABR stack may include other resolutions that you do not specify here, depending on the Max
 * renditions setting. Use Allowed renditions to specify a list of possible resolutions in your ABR stack. * (Required)
 * The number of resolutions that you specify must be equal to, or greater than, the Max renditions setting. *
 * MediaConvert will create an ABR stack exclusively from the list of resolutions that you specify. * Some resolutions
 * in the Allowed renditions list may not be included, however you can force a resolution to be included by setting
 * Required to ENABLED. * You must specify at least one resolution that is greater than or equal to any resolutions that
 * you specify in Min top rendition size or Min bottom rendition size. * If you specify Allowed renditions, you must not
 * specify a separate rule for Force include renditions.
 */
final class RuleType
{
    public const ALLOWED_RENDITIONS = 'ALLOWED_RENDITIONS';
    public const FORCE_INCLUDE_RENDITIONS = 'FORCE_INCLUDE_RENDITIONS';
    public const MIN_BOTTOM_RENDITION_SIZE = 'MIN_BOTTOM_RENDITION_SIZE';
    public const MIN_TOP_RENDITION_SIZE = 'MIN_TOP_RENDITION_SIZE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALLOWED_RENDITIONS => true,
            self::FORCE_INCLUDE_RENDITIONS => true,
            self::MIN_BOTTOM_RENDITION_SIZE => true,
            self::MIN_TOP_RENDITION_SIZE => true,
        ][$value]);
    }
}
