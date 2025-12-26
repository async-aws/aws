<?php

namespace AsyncAws\Comprehend\Enum;

final class InvalidRequestReason
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const INVALID_DOCUMENT = 'INVALID_DOCUMENT';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INVALID_DOCUMENT => true,
        ][$value]);
    }
}
