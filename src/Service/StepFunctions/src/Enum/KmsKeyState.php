<?php

namespace AsyncAws\StepFunctions\Enum;

final class KmsKeyState
{
    public const CREATING = 'CREATING';
    public const DISABLED = 'DISABLED';
    public const PENDING_DELETION = 'PENDING_DELETION';
    public const PENDING_IMPORT = 'PENDING_IMPORT';
    public const UNAVAILABLE = 'UNAVAILABLE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CREATING => true,
            self::DISABLED => true,
            self::PENDING_DELETION => true,
            self::PENDING_IMPORT => true,
            self::UNAVAILABLE => true,
        ][$value]);
    }
}
