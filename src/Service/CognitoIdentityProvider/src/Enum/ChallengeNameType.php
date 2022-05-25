<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

/**
 * The name of the challenge that you're responding to with this call. This is returned in the `AdminInitiateAuth`
 * response if you must pass another challenge.
 *
 * - `MFA_SETUP`: If MFA is required, users who don't have at least one of the MFA methods set up are presented with an
 *   `MFA_SETUP` challenge. The user must set up at least one MFA type to continue to authenticate.
 * - `SELECT_MFA_TYPE`: Selects the MFA type. Valid MFA options are `SMS_MFA` for text SMS MFA, and `SOFTWARE_TOKEN_MFA`
 *   for time-based one-time password (TOTP) software token MFA.
 * - `SMS_MFA`: Next challenge is to supply an `SMS_MFA_CODE`, delivered via SMS.
 * - `PASSWORD_VERIFIER`: Next challenge is to supply `PASSWORD_CLAIM_SIGNATURE`, `PASSWORD_CLAIM_SECRET_BLOCK`, and
 *   `TIMESTAMP` after the client-side SRP calculations.
 * - `CUSTOM_CHALLENGE`: This is returned if your custom authentication flow determines that the user should pass
 *   another challenge before tokens are issued.
 * - `DEVICE_SRP_AUTH`: If device tracking was activated in your user pool and the previous challenges were passed, this
 *   challenge is returned so that Amazon Cognito can start tracking this device.
 * - `DEVICE_PASSWORD_VERIFIER`: Similar to `PASSWORD_VERIFIER`, but for devices only.
 * - `ADMIN_NO_SRP_AUTH`: This is returned if you must authenticate with `USERNAME` and `PASSWORD` directly. An app
 *   client must be enabled to use this flow.
 * - `NEW_PASSWORD_REQUIRED`: For users who are required to change their passwords after successful first login. Respond
 *   to this challenge with `NEW_PASSWORD` and any required attributes that Amazon Cognito returned in the
 *   `requiredAttributes` parameter. You can also set values for attributes that aren't required by your user pool and
 *   that your app client can write. For more information, see AdminRespondToAuthChallenge.
 *
 *   > In a `NEW_PASSWORD_REQUIRED` challenge response, you can't modify a required attribute that already has a value.
 *   > In `AdminRespondToAuthChallenge`, set a value for any keys that Amazon Cognito returned in the
 *   > `requiredAttributes` parameter, then use the `AdminUpdateUserAttributes` API operation to modify the value of any
 *   > additional attributes.
 *
 * - `MFA_SETUP`: For users who are required to set up an MFA factor before they can sign in. The MFA types activated
 *   for the user pool will be listed in the challenge parameters `MFA_CAN_SETUP` value.
 *   To set up software token MFA, use the session returned here from `InitiateAuth` as an input to
 *   `AssociateSoftwareToken`, and use the session returned by `VerifySoftwareToken` as an input to
 *   `RespondToAuthChallenge` with challenge name `MFA_SETUP` to complete sign-in. To set up SMS MFA, users will need
 *   help from an administrator to add a phone number to their account and then call `InitiateAuth` again to restart
 *   sign-in.
 *
 * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminRespondToAuthChallenge.html
 */
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
