<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to WHEN_POSSIBLE, input DD+ audio will be passed through if it is present on the input. this detection is
 * dynamic over the life of the transcode. Inputs that alternate between DD+ and non-DD+ content will have a consistent
 * DD+ output as the system alternates between passthrough and encoding.
 */
final class Eac3PassthroughControl
{
    public const NO_PASSTHROUGH = 'NO_PASSTHROUGH';
    public const WHEN_POSSIBLE = 'WHEN_POSSIBLE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NO_PASSTHROUGH => true,
            self::WHEN_POSSIBLE => true,
        ][$value]);
    }
}
