<?php

namespace AsyncAws\AppSync\Enum;

final class ResolverKind
{
    public const PIPELINE = 'PIPELINE';
    public const UNIT = 'UNIT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

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
