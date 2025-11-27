<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Controls what buffer model to use for accurate interleaving. If set to MULTIPLEX, use multiplex buffer model. If set
 * to NONE, this can lead to lower latency, but low-memory devices may not be able to play back the stream without
 * interruptions.
 */
final class M2tsBufferModel
{
    public const MULTIPLEX = 'MULTIPLEX';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MULTIPLEX => true,
            self::NONE => true,
        ][$value]);
    }
}
