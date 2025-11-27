<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether MediaConvert should use any dynamic range control metadata from your input file. Keep the default
 * value, Custom, to provide dynamic range control values in your job settings. Choose Follow source to use the metadata
 * from your input. Related settings--Use these settings to specify your dynamic range control values: Dynamic range
 * compression line and Dynamic range compression RF. When you keep the value Custom for Dynamic range control and you
 * don't specify values for the related settings, MediaConvert uses default values for those settings.
 */
final class Eac3AtmosDynamicRangeControl
{
    public const INITIALIZE_FROM_SOURCE = 'INITIALIZE_FROM_SOURCE';
    public const SPECIFIED = 'SPECIFIED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INITIALIZE_FROM_SOURCE => true,
            self::SPECIFIED => true,
        ][$value]);
    }
}
