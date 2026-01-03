<?php

namespace AsyncAws\Rekognition\Enum;

final class EmotionName
{
    public const ANGRY = 'ANGRY';
    public const CALM = 'CALM';
    public const CONFUSED = 'CONFUSED';
    public const DISGUSTED = 'DISGUSTED';
    public const FEAR = 'FEAR';
    public const HAPPY = 'HAPPY';
    public const SAD = 'SAD';
    public const SURPRISED = 'SURPRISED';
    public const UNKNOWN = 'UNKNOWN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ANGRY => true,
            self::CALM => true,
            self::CONFUSED => true,
            self::DISGUSTED => true,
            self::FEAR => true,
            self::HAPPY => true,
            self::SAD => true,
            self::SURPRISED => true,
            self::UNKNOWN => true,
        ][$value]);
    }
}
