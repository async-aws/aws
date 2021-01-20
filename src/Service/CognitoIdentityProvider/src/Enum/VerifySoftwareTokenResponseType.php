<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

/**
 * The status of the verify software token.
 */
final class VerifySoftwareTokenResponseType
{
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
