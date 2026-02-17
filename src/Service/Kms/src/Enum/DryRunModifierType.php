<?php

namespace AsyncAws\Kms\Enum;

final class DryRunModifierType
{
    public const IGNORE_CIPHERTEXT = 'IGNORE_CIPHERTEXT';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::IGNORE_CIPHERTEXT => true,
        ][$value]);
    }
}
