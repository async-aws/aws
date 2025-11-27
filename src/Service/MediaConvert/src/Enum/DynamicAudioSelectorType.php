<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify which audio tracks to dynamically select from your source. To select all audio tracks: Keep the default
 * value, All tracks. To select all audio tracks with a specific language code: Choose Language code. When you do, you
 * must also specify a language code under the Language code setting. If there is no matching Language code in your
 * source, then no track will be selected.
 */
final class DynamicAudioSelectorType
{
    public const ALL_TRACKS = 'ALL_TRACKS';
    public const LANGUAGE_CODE = 'LANGUAGE_CODE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_TRACKS => true,
            self::LANGUAGE_CODE => true,
        ][$value]);
    }
}
