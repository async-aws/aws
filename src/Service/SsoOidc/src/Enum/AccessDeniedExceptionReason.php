<?php

namespace AsyncAws\SsoOidc\Enum;

final class AccessDeniedExceptionReason
{
    public const KMS_ACCESS_DENIED_EXCEPTION = 'KMS_AccessDeniedException';

    public static function exists(string $value): bool
    {
        return isset([
            self::KMS_ACCESS_DENIED_EXCEPTION => true,
        ][$value]);
    }
}
