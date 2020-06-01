<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class ChallengeNameType
{
    public const ADMIN_NO_SRP_AUTH = 'ADMIN_NO_SRP_AUTH';
    public const CUSTOM_CHALLENGE = 'CUSTOM_CHALLENGE';
    public const DEVICE_PASSWORD_VERIFIER = 'DEVICE_PASSWORD_VERIFIER';
    public const DEVICE_SRP_AUTH = 'DEVICE_SRP_AUTH';
    public const MFA_SETUP = 'MFA_SETUP';
    public const NEW_PASSWORD_REQUIRED = 'NEW_PASSWORD_REQUIRED';
    public const PASSWORD_VERIFIER = 'PASSWORD_VERIFIER';
    public const SELECT_MFA_TYPE = 'SELECT_MFA_TYPE';
    public const SMS_MFA = 'SMS_MFA';
    public const SOFTWARE_TOKEN_MFA = 'SOFTWARE_TOKEN_MFA';

    public static function exists(string $value): bool
    {
        return isset([
            self::ADMIN_NO_SRP_AUTH => true,
            self::CUSTOM_CHALLENGE => true,
            self::DEVICE_PASSWORD_VERIFIER => true,
            self::DEVICE_SRP_AUTH => true,
            self::MFA_SETUP => true,
            self::NEW_PASSWORD_REQUIRED => true,
            self::PASSWORD_VERIFIER => true,
            self::SELECT_MFA_TYPE => true,
            self::SMS_MFA => true,
            self::SOFTWARE_TOKEN_MFA => true,
        ][$value]);
    }
}
