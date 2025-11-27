<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class VerifySoftwareTokenResponseType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ERROR = 'ERROR';
    public const SUCCESS = 'SUCCESS';

    public static function exists(string $value): bool
    {
        return isset([
            self::ERROR => true,
            self::SUCCESS => true,
        ][$value]);
    }
}
