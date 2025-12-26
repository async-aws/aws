<?php

namespace AsyncAws\Rekognition\Enum;

final class Attribute
{
    public const AGE_RANGE = 'AGE_RANGE';
    public const ALL = 'ALL';
    public const BEARD = 'BEARD';
    public const DEFAULT = 'DEFAULT';
    public const EMOTIONS = 'EMOTIONS';
    public const EYEGLASSES = 'EYEGLASSES';
    public const EYES_OPEN = 'EYES_OPEN';
    public const EYE_DIRECTION = 'EYE_DIRECTION';
    public const FACE_OCCLUDED = 'FACE_OCCLUDED';
    public const GENDER = 'GENDER';
    public const MOUTH_OPEN = 'MOUTH_OPEN';
    public const MUSTACHE = 'MUSTACHE';
    public const SMILE = 'SMILE';
    public const SUNGLASSES = 'SUNGLASSES';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AGE_RANGE => true,
            self::ALL => true,
            self::BEARD => true,
            self::DEFAULT => true,
            self::EMOTIONS => true,
            self::EYEGLASSES => true,
            self::EYES_OPEN => true,
            self::EYE_DIRECTION => true,
            self::FACE_OCCLUDED => true,
            self::GENDER => true,
            self::MOUTH_OPEN => true,
            self::MUSTACHE => true,
            self::SMILE => true,
            self::SUNGLASSES => true,
        ][$value]);
    }
}
