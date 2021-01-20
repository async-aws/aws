<?php

namespace AsyncAws\Rekognition\Enum;

/**
 * The predicted gender of the face.
 */
final class GenderType
{
    public const FEMALE = 'Female';
    public const MALE = 'Male';

    public static function exists(string $value): bool
    {
        return isset([
            self::FEMALE => true,
            self::MALE => true,
        ][$value]);
    }
}
