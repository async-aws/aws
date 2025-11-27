<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether your DASH profile is on-demand or main. When you choose Main profile, the service signals
 * urn:mpeg:dash:profile:isoff-main:2011 in your .mpd DASH manifest. When you choose On-demand, the service signals
 * urn:mpeg:dash:profile:isoff-on-demand:2011 in your .mpd. When you choose On-demand, you must also set the output
 * group setting Segment control to Single file.
 */
final class CmafMpdProfile
{
    public const MAIN_PROFILE = 'MAIN_PROFILE';
    public const ON_DEMAND_PROFILE = 'ON_DEMAND_PROFILE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MAIN_PROFILE => true,
            self::ON_DEMAND_PROFILE => true,
        ][$value]);
    }
}
