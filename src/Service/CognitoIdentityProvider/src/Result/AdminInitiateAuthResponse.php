<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AuthenticationResultType;
use AsyncAws\CognitoIdentityProvider\ValueObject\NewDeviceMetadataType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Initiates the authentication response, as an administrator.
 */
class AdminInitiateAuthResponse extends Result
{
    /**
     * The name of the challenge that you're responding to with this call. This is returned in the `AdminInitiateAuth`
     * response if you must pass another challenge.
     *
     * - `WEB_AUTHN`: Respond to the challenge with the results of a successful authentication with a passkey, or webauthN,
     *   factor. These are typically biometric devices or security keys.
     * - `PASSWORD`: Respond with `USER_PASSWORD_AUTH` parameters: `USERNAME` (required), `PASSWORD` (required),
     *   `SECRET_HASH` (required if the app client is configured with a client secret), `DEVICE_KEY`.
     * - `PASSWORD_SRP`: Respond with `USER_SRP_AUTH` parameters: `USERNAME` (required), `SRP_A` (required), `SECRET_HASH`
     *   (required if the app client is configured with a client secret), `DEVICE_KEY`.
     * - `SELECT_CHALLENGE`: Respond to the challenge with `USERNAME` and an `ANSWER` that matches one of the challenge
     *   types in the `AvailableChallenges` response parameter.
     * - `MFA_SETUP`: If MFA is required, users who don't have at least one of the MFA methods set up are presented with an
     *   `MFA_SETUP` challenge. The user must set up at least one MFA type to continue to authenticate.
     * - `SELECT_MFA_TYPE`: Selects the MFA type. Valid MFA options are `SMS_MFA` for SMS message MFA, `EMAIL_OTP` for email
     *   message MFA, and `SOFTWARE_TOKEN_MFA` for time-based one-time password (TOTP) software token MFA.
     * - `SMS_MFA`: Next challenge is to supply an `SMS_MFA_CODE`that your user pool delivered in an SMS message.
     * - `EMAIL_OTP`: Next challenge is to supply an `EMAIL_OTP_CODE` that your user pool delivered in an email message.
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
     *   that your app client can write. For more information, see AdminRespondToAuthChallenge [^1].
     *
     *   Amazon Cognito only returns this challenge for users who have temporary passwords. Because of this, and because in
     *   some cases you can create users who don't have values for required attributes, take care to collect and submit
     *   required-attribute values for all users who don't have passwords. You can create a user in the Amazon Cognito
     *   console without, for example, a required `birthdate` attribute. The API response from Amazon Cognito won't prompt
     *   you to submit a birthdate for the user if they don't have a password.
     *
     *   > In a `NEW_PASSWORD_REQUIRED` challenge response, you can't modify a required attribute that already has a value.
     *   > In `AdminRespondToAuthChallenge`, set a value for any keys that Amazon Cognito returned in the
     *   > `requiredAttributes` parameter, then use the `AdminUpdateUserAttributes` API operation to modify the value of any
     *   > additional attributes.
     *
     * - `MFA_SETUP`: For users who are required to set up an MFA factor before they can sign in. The MFA types activated
     *   for the user pool will be listed in the challenge parameters `MFAS_CAN_SETUP` value.
     *
     *   To set up software token MFA, use the session returned here from `InitiateAuth` as an input to
     *   `AssociateSoftwareToken`, and use the session returned by `VerifySoftwareToken` as an input to
     *   `RespondToAuthChallenge` with challenge name `MFA_SETUP` to complete sign-in. To set up SMS MFA, users will need
     *   help from an administrator to add a phone number to their account and then call `InitiateAuth` again to restart
     *   sign-in.
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminRespondToAuthChallenge.html
     *
     * @var ChallengeNameType::*|null
     */
    private $challengeName;

    /**
     * The session that must be passed to challenge-response requests. If an `AdminInitiateAuth` or
     * `AdminRespondToAuthChallenge` API request determines that the caller must pass another challenge, Amazon Cognito
     * returns a session ID and the parameters of the next challenge. Pass this session Id in the `Session` parameter of
     * `AdminRespondToAuthChallenge`.
     *
     * @var string|null
     */
    private $session;

    /**
     * The challenge parameters. These are returned to you in the `AdminInitiateAuth` response if you must pass another
     * challenge. The responses in this parameter should be used to compute inputs to the next call
     * (`AdminRespondToAuthChallenge`).
     *
     * All challenges require `USERNAME` and `SECRET_HASH` (if applicable).
     *
     * The value of the `USER_ID_FOR_SRP` attribute is the user's actual username, not an alias (such as email address or
     * phone number), even if you specified an alias in your call to `AdminInitiateAuth`. This happens because, in the
     * `AdminRespondToAuthChallenge` API `ChallengeResponses`, the `USERNAME` attribute can't be an alias.
     *
     * @var array<string, string>
     */
    private $challengeParameters;

    /**
     * The outcome of successful authentication. This is only returned if the user pool has no additional challenges to
     * return. If Amazon Cognito returns another challenge, the response includes `ChallengeName`, `ChallengeParameters`,
     * and `Session` so that your user can answer the challenge.
     *
     * @var AuthenticationResultType|null
     */
    private $authenticationResult;

    public function getAuthenticationResult(): ?AuthenticationResultType
    {
        $this->initialize();

        return $this->authenticationResult;
    }

    /**
     * @return ChallengeNameType::*|null
     */
    public function getChallengeName(): ?string
    {
        $this->initialize();

        return $this->challengeName;
    }

    /**
     * @return array<string, string>
     */
    public function getChallengeParameters(): array
    {
        $this->initialize();

        return $this->challengeParameters;
    }

    public function getSession(): ?string
    {
        $this->initialize();

        return $this->session;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->challengeName = isset($data['ChallengeName']) ? (string) $data['ChallengeName'] : null;
        $this->session = isset($data['Session']) ? (string) $data['Session'] : null;
        $this->challengeParameters = empty($data['ChallengeParameters']) ? [] : $this->populateResultChallengeParametersType($data['ChallengeParameters']);
        $this->authenticationResult = empty($data['AuthenticationResult']) ? null : $this->populateResultAuthenticationResultType($data['AuthenticationResult']);
    }

    private function populateResultAuthenticationResultType(array $json): AuthenticationResultType
    {
        return new AuthenticationResultType([
            'AccessToken' => isset($json['AccessToken']) ? (string) $json['AccessToken'] : null,
            'ExpiresIn' => isset($json['ExpiresIn']) ? (int) $json['ExpiresIn'] : null,
            'TokenType' => isset($json['TokenType']) ? (string) $json['TokenType'] : null,
            'RefreshToken' => isset($json['RefreshToken']) ? (string) $json['RefreshToken'] : null,
            'IdToken' => isset($json['IdToken']) ? (string) $json['IdToken'] : null,
            'NewDeviceMetadata' => empty($json['NewDeviceMetadata']) ? null : $this->populateResultNewDeviceMetadataType($json['NewDeviceMetadata']),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultChallengeParametersType(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    private function populateResultNewDeviceMetadataType(array $json): NewDeviceMetadataType
    {
        return new NewDeviceMetadataType([
            'DeviceKey' => isset($json['DeviceKey']) ? (string) $json['DeviceKey'] : null,
            'DeviceGroupKey' => isset($json['DeviceGroupKey']) ? (string) $json['DeviceGroupKey'] : null,
        ]);
    }
}
