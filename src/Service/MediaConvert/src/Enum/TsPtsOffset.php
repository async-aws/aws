<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the initial presentation timestamp (PTS) offset for your transport stream output. To let MediaConvert
 * automatically determine the initial PTS offset: Keep the default value, Auto. We recommend that you choose Auto for
 * the widest player compatibility. The initial PTS will be at least two seconds and vary depending on your output's
 * bitrate, HRD buffer size and HRD buffer initial fill percentage. To manually specify an initial PTS offset: Choose
 * Seconds. Then specify the number of seconds with PTS offset.
 */
final class TsPtsOffset
{
    public const AUTO = 'AUTO';
    public const SECONDS = 'SECONDS';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::SECONDS => true,
        ][$value]);
    }
}
