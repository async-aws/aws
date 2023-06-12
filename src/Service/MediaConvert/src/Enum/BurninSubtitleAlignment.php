<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the alignment of your captions. If no explicit x_position is provided, setting alignment to centered will
 * placethe captions at the bottom center of the output. Similarly, setting a left alignment willalign captions to the
 * bottom left of the output. If x and y positions are given in conjunction with the alignment parameter, the font will
 * be justified (either left or centered) relative to those coordinates.
 */
final class BurninSubtitleAlignment
{
    public const AUTO = 'AUTO';
    public const CENTERED = 'CENTERED';
    public const LEFT = 'LEFT';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::CENTERED => true,
            self::LEFT => true,
        ][$value]);
    }
}
