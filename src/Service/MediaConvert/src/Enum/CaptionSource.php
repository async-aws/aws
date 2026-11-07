<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the source of the captions for this caption description. Choose Captions input source
 * (INPUT_CAPTION_SELECTOR) to use a caption selector from your input. Choose Smart subtitling (SMART_SUBTITLE) to have
 * MediaConvert and Elemental Inference generate subtitles automatically from an audio source that you specify.
 */
final class CaptionSource
{
    public const INPUT_CAPTION_SELECTOR = 'INPUT_CAPTION_SELECTOR';
    public const SMART_SUBTITLE = 'SMART_SUBTITLE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INPUT_CAPTION_SELECTOR => true,
            self::SMART_SUBTITLE => true,
        ][$value]);
    }
}
