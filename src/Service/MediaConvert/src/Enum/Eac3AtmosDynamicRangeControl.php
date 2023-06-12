<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether MediaConvert should use any dynamic range control metadata from your input file. Keep the default
 * value, Custom (SPECIFIED), to provide dynamic range control values in your job settings. Choose Follow source
 * (INITIALIZE_FROM_SOURCE) to use the metadata from your input. Related settings--Use these settings to specify your
 * dynamic range control values: Dynamic range compression line (DynamicRangeCompressionLine) and Dynamic range
 * compression RF (DynamicRangeCompressionRf). When you keep the value Custom (SPECIFIED) for Dynamic range control
 * (DynamicRangeControl) and you don't specify values for the related settings, MediaConvert uses default values for
 * those settings.
 */
final class Eac3AtmosDynamicRangeControl
{
    public const INITIALIZE_FROM_SOURCE = 'INITIALIZE_FROM_SOURCE';
    public const SPECIFIED = 'SPECIFIED';

    public static function exists(string $value): bool
    {
        return isset([
            self::INITIALIZE_FROM_SOURCE => true,
            self::SPECIFIED => true,
        ][$value]);
    }
}
