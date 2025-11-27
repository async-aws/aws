<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class VerifySoftwareTokenResponseType
{
    public const ERROR = 'ERROR';
    public const SUCCESS = 'SUCCESS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ERROR => true,
            self::SUCCESS => true,
        ][$value]);
    }
}
