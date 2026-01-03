<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the scan line type for the output. Keep the default value, Progressive to create a progressive output,
 * regardless of the scan type of your input. Use Top field first or Bottom field first to create an output that's
 * interlaced with the same field polarity throughout. Use Follow, default top or Follow, default bottom to produce
 * outputs with the same field polarity as the source. For jobs that have multiple inputs, the output field polarity
 * might change over the course of the output. Follow behavior depends on the input scan type. If the source is
 * interlaced, the output will be interlaced with the same polarity as the source. If the source is progressive, the
 * output will be interlaced with top field bottom field first, depending on which of the Follow options you choose.
 */
final class XavcInterlaceMode
{
    public const BOTTOM_FIELD = 'BOTTOM_FIELD';
    public const FOLLOW_BOTTOM_FIELD = 'FOLLOW_BOTTOM_FIELD';
    public const FOLLOW_TOP_FIELD = 'FOLLOW_TOP_FIELD';
    public const PROGRESSIVE = 'PROGRESSIVE';
    public const TOP_FIELD = 'TOP_FIELD';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BOTTOM_FIELD => true,
            self::FOLLOW_BOTTOM_FIELD => true,
            self::FOLLOW_TOP_FIELD => true,
            self::PROGRESSIVE => true,
            self::TOP_FIELD => true,
        ][$value]);
    }
}
