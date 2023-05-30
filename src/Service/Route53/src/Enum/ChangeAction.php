<?php

namespace AsyncAws\Route53\Enum;

final class ChangeAction
{
    public const CREATE = 'CREATE';
    public const DELETE = 'DELETE';
    public const UPSERT = 'UPSERT';

    public static function exists(string $value): bool
    {
        return isset([
            self::CREATE => true,
            self::DELETE => true,
            self::UPSERT => true,
        ][$value]);
    }
}
