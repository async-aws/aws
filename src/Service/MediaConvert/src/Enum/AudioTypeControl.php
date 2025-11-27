<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to FOLLOW_INPUT, if the input contains an ISO 639 audio_type, then that value is passed through to the
 * output. If the input contains no ISO 639 audio_type, the value in Audio Type is included in the output. Otherwise the
 * value in Audio Type is included in the output. Note that this field and audioType are both ignored if
 * audioDescriptionBroadcasterMix is set to BROADCASTER_MIXED_AD.
 */
final class AudioTypeControl
{
    public const FOLLOW_INPUT = 'FOLLOW_INPUT';
    public const USE_CONFIGURED = 'USE_CONFIGURED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FOLLOW_INPUT => true,
            self::USE_CONFIGURED => true,
        ][$value]);
    }
}
