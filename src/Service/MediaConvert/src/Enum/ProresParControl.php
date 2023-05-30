<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Specify how the service determines the pixel aspect ratio (PAR) for this output. The default behavior,
 * Follow source (INITIALIZE_FROM_SOURCE), uses the PAR from your input video for your output. To specify a different
 * PAR in the console, choose any value other than Follow source. To specify a different PAR by editing the JSON job
 * specification, choose SPECIFIED. When you choose SPECIFIED for this setting, you must also specify values for the
 * parNumerator and parDenominator settings.
 */
final class ProresParControl
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
