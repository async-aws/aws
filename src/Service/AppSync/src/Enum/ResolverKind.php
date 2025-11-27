<?php

namespace AsyncAws\AppSync\Enum;

final class ResolverKind
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const PIPELINE = 'PIPELINE';
    public const UNIT = 'UNIT';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PIPELINE => true,
            self::UNIT => true,
        ][$value]);
    }
}
