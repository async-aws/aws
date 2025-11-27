<?php

namespace AsyncAws\SsoOidc\Enum;

final class AccessDeniedExceptionReason
{
    public const KMS_ACCESS_DENIED_EXCEPTION = 'KMS_AccessDeniedException';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KMS_ACCESS_DENIED_EXCEPTION => true,
        ][$value]);
    }
}
