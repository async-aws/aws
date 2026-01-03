<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * A job's status can be SUBMITTED, PROGRESSING, COMPLETE, CANCELED, or ERROR.
 */
final class JobStatus
{
    public const CANCELED = 'CANCELED';
    public const COMPLETE = 'COMPLETE';
    public const ERROR = 'ERROR';
    public const PROGRESSING = 'PROGRESSING';
    public const SUBMITTED = 'SUBMITTED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CANCELED => true,
            self::COMPLETE => true,
            self::ERROR => true,
            self::PROGRESSING => true,
            self::SUBMITTED => true,
        ][$value]);
    }
}
