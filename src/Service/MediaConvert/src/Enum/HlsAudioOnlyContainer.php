<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting only in audio-only outputs. Choose MPEG-2 Transport Stream (M2TS) to create a file in an MPEG2-TS
 * container. Keep the default value Automatic to create a raw audio-only file with no container. Regardless of the
 * value that you specify here, if this output has video, the service will place outputs into an MPEG2-TS container.
 */
final class HlsAudioOnlyContainer
{
    public const AUTOMATIC = 'AUTOMATIC';
    public const M2TS = 'M2TS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTOMATIC => true,
            self::M2TS => true,
        ][$value]);
    }
}
