<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to PCR_EVERY_PES_PACKET, a Program Clock Reference value is inserted for every Packetized Elementary Stream
 * (PES) header. This is effective only when the PCR PID is the same as the video or audio elementary stream.
 */
final class M2tsPcrControl
{
    public const CONFIGURED_PCR_PERIOD = 'CONFIGURED_PCR_PERIOD';
    public const PCR_EVERY_PES_PACKET = 'PCR_EVERY_PES_PACKET';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CONFIGURED_PCR_PERIOD => true,
            self::PCR_EVERY_PES_PACKET => true,
        ][$value]);
    }
}
