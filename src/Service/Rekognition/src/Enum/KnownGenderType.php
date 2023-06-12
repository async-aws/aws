<?php

namespace AsyncAws\Rekognition\Enum;

/**
 * A list of enum string of possible gender values that Celebrity returns.
 */
final class KnownGenderType
{
    public const FEMALE = 'Female';
    public const MALE = 'Male';
    public const NONBINARY = 'Nonbinary';
    public const UNLISTED = 'Unlisted';

    public static function exists(string $value): bool
    {
        return isset([
            self::FEMALE => true,
            self::MALE => true,
            self::NONBINARY => true,
            self::UNLISTED => true,
        ][$value]);
    }
}
