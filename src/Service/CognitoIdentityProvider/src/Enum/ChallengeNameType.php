<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class ChallengeNameType
{
    public const ADMIN_NO_SRP_AUTH = 'ADMIN_NO_SRP_AUTH';
    public const CUSTOM_CHALLENGE = 'CUSTOM_CHALLENGE';
    public const DEVICE_PASSWORD_VERIFIER = 'DEVICE_PASSWORD_VERIFIER';
    public const DEVICE_SRP_AUTH = 'DEVICE_SRP_AUTH';
    public const EMAIL_OTP = 'EMAIL_OTP';
    public const MFA_SETUP = 'MFA_SETUP';
    public const NEW_PASSWORD_REQUIRED = 'NEW_PASSWORD_REQUIRED';
    public const PASSWORD = 'PASSWORD';
    public const PASSWORD_SRP = 'PASSWORD_SRP';
    public const PASSWORD_VERIFIER = 'PASSWORD_VERIFIER';
    public const SELECT_CHALLENGE = 'SELECT_CHALLENGE';
    public const SELECT_MFA_TYPE = 'SELECT_MFA_TYPE';
    public const SMS_MFA = 'SMS_MFA';
    public const SMS_OTP = 'SMS_OTP';
    public const SOFTWARE_TOKEN_MFA = 'SOFTWARE_TOKEN_MFA';
    public const WEB_AUTHN = 'WEB_AUTHN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ADMIN_NO_SRP_AUTH => true,
            self::CUSTOM_CHALLENGE => true,
            self::DEVICE_PASSWORD_VERIFIER => true,
            self::DEVICE_SRP_AUTH => true,
            self::EMAIL_OTP => true,
            self::MFA_SETUP => true,
            self::NEW_PASSWORD_REQUIRED => true,
            self::PASSWORD => true,
            self::PASSWORD_SRP => true,
            self::PASSWORD_VERIFIER => true,
            self::SELECT_CHALLENGE => true,
            self::SELECT_MFA_TYPE => true,
            self::SMS_MFA => true,
            self::SMS_OTP => true,
            self::SOFTWARE_TOKEN_MFA => true,
            self::WEB_AUTHN => true,
        ][$value]);
    }
}
