<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose BROADCASTER_MIXED_AD when the input contains pre-mixed main audio + audio description (AD) as a stereo pair.
 * The value for AudioType will be set to 3, which signals to downstream systems that this stream contains "broadcaster
 * mixed AD". Note that the input received by the encoder must contain pre-mixed audio; the encoder does not perform the
 * mixing. When you choose BROADCASTER_MIXED_AD, the encoder ignores any values you provide in AudioType and
 * FollowInputAudioType. Choose NORMAL when the input does not contain pre-mixed audio + audio description (AD). In this
 * case, the encoder will use any values you provide for AudioType and FollowInputAudioType.
 */
final class AacAudioDescriptionBroadcasterMix
{
    public const BROADCASTER_MIXED_AD = 'BROADCASTER_MIXED_AD';
    public const NORMAL = 'NORMAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BROADCASTER_MIXED_AD => true,
            self::NORMAL => true,
        ][$value]);
    }
}
