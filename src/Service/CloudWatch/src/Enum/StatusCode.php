<?php

namespace AsyncAws\CloudWatch\Enum;

final class StatusCode
{
    public const COMPLETE = 'Complete';
    public const FORBIDDEN = 'Forbidden';
    public const INTERNAL_ERROR = 'InternalError';
    public const PARTIAL_DATA = 'PartialData';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COMPLETE => true,
            self::FORBIDDEN => true,
            self::INTERNAL_ERROR => true,
            self::PARTIAL_DATA => true,
        ][$value]);
    }
}
