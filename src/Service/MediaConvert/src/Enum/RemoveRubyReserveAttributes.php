<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optionally remove any tts:rubyReserve attributes present in your input, that do  not have a tts:ruby attribute in the
 * same element, from your output. Use if your vertical Japanese output captions have alignment issues. To remove ruby
 * reserve attributes when present: Choose Enabled. To not remove any ruby reserve attributes: Keep the default value,
 * Disabled.
 */
final class RemoveRubyReserveAttributes
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
