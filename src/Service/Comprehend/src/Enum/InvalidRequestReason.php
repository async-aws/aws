<?php

namespace AsyncAws\Comprehend\Enum;

final class InvalidRequestReason
{
    public const INVALID_DOCUMENT = 'INVALID_DOCUMENT';

    public static function exists(string $value): bool
    {
        return isset([
            self::INVALID_DOCUMENT => true,
        ][$value]);
    }
}
