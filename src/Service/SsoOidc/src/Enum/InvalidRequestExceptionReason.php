<?php

namespace AsyncAws\SsoOidc\Enum;

final class InvalidRequestExceptionReason
{
    public const KMS_DISABLED_EXCEPTION = 'KMS_DisabledException';
    public const KMS_INVALID_KEY_USAGE_EXCEPTION = 'KMS_InvalidKeyUsageException';
    public const KMS_INVALID_STATE_EXCEPTION = 'KMS_InvalidStateException';
    public const KMS_NOT_FOUND_EXCEPTION = 'KMS_NotFoundException';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KMS_DISABLED_EXCEPTION => true,
            self::KMS_INVALID_KEY_USAGE_EXCEPTION => true,
            self::KMS_INVALID_STATE_EXCEPTION => true,
            self::KMS_NOT_FOUND_EXCEPTION => true,
        ][$value]);
    }
}
