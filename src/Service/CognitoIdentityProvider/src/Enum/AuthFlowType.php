<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

/**
 * The authentication flow for this call to run. The API action will depend on this value. For example:.
 *
 * - `REFRESH_TOKEN_AUTH` will take in a valid refresh token and return new tokens.
 * - `USER_SRP_AUTH` will take in `USERNAME` and `SRP_A` and return the Secure Remote Password (SRP) protocol variables
 *   to be used for next challenge execution.
 * - `ADMIN_USER_PASSWORD_AUTH` will take in `USERNAME` and `PASSWORD` and return the next challenge or tokens.
 *
 * Valid values include:
 *
 * - `USER_SRP_AUTH`: Authentication flow for the Secure Remote Password (SRP) protocol.
 * - `REFRESH_TOKEN_AUTH`/`REFRESH_TOKEN`: Authentication flow for refreshing the access token and ID token by supplying
 *   a valid refresh token.
 * - `CUSTOM_AUTH`: Custom authentication flow.
 * - `ADMIN_NO_SRP_AUTH`: Non-SRP authentication flow; you can pass in the USERNAME and PASSWORD directly if the flow is
 *   enabled for calling the app client.
 * - `ADMIN_USER_PASSWORD_AUTH`: Admin-based user password authentication. This replaces the `ADMIN_NO_SRP_AUTH`
 *   authentication flow. In this flow, Amazon Cognito receives the password in the request instead of using the SRP
 *   process to verify passwords.
 */
final class AuthFlowType
{
    public const ADMIN_NO_SRP_AUTH = 'ADMIN_NO_SRP_AUTH';
    public const ADMIN_USER_PASSWORD_AUTH = 'ADMIN_USER_PASSWORD_AUTH';
    public const CUSTOM_AUTH = 'CUSTOM_AUTH';
    public const REFRESH_TOKEN = 'REFRESH_TOKEN';
    public const REFRESH_TOKEN_AUTH = 'REFRESH_TOKEN_AUTH';
    public const USER_PASSWORD_AUTH = 'USER_PASSWORD_AUTH';
    public const USER_SRP_AUTH = 'USER_SRP_AUTH';

    public static function exists(string $value): bool
    {
        return isset([
            self::ADMIN_NO_SRP_AUTH => true,
            self::ADMIN_USER_PASSWORD_AUTH => true,
            self::CUSTOM_AUTH => true,
            self::REFRESH_TOKEN => true,
            self::REFRESH_TOKEN_AUTH => true,
            self::USER_PASSWORD_AUTH => true,
            self::USER_SRP_AUTH => true,
        ][$value]);
    }
}
