<?php

namespace AsyncAws\Kms\Enum;

final class OriginType
{
    public const AWS_CLOUDHSM = 'AWS_CLOUDHSM';
    public const AWS_KMS = 'AWS_KMS';
    public const EXTERNAL = 'EXTERNAL';
    public const EXTERNAL_KEY_STORE = 'EXTERNAL_KEY_STORE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AWS_CLOUDHSM => true,
            self::AWS_KMS => true,
            self::EXTERNAL => true,
            self::EXTERNAL_KEY_STORE => true,
        ][$value]);
    }
}
