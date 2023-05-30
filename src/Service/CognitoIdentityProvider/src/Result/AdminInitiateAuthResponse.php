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
     * - `MFA_SETUP`: If MFA is required, users who don't have at least one of the MFA methods set up are presented with an
     *   `MFA_SETUP` challenge. The user must set up at least one MFA type to continue to authenticate.
     * -
     * - `SELECT_MFA_TYPE`: Selects the MFA type. Valid MFA options are `SMS_MFA` for text SMS MFA, and `SOFTWARE_TOKEN_MFA`
     *   for time-based one-time password (TOTP) software token MFA.
     * -
     * - `SMS_MFA`: Next challenge is to supply an `SMS_MFA_CODE`, delivered via SMS.
     * -
     * - `PASSWORD_VERIFIER`: Next challenge is to supply `PASSWORD_CLAIM_SIGNATURE`, `PASSWORD_CLAIM_SECRET_BLOCK`, and
     *   `TIMESTAMP` after the client-side SRP calculations.
     * -
     * - `CUSTOM_CHALLENGE`: This is returned if your custom authentication flow determines that the user should pass
     *   another challenge before tokens are issued.
     * -
     * - `DEVICE_SRP_AUTH`: If device tracking was activated in your user pool and the previous challenges were passed, this
     *   challenge is returned so that Amazon Cognito can start tracking this device.
     * -
     * - `DEVICE_PASSWORD_VERIFIER`: Similar to `PASSWORD_VERIFIER`, but for devices only.
     * -
     * - `ADMIN_NO_SRP_AUTH`: This is returned if you must authenticate with `USERNAME` and `PASSWORD` directly. An app
     *   client must be enabled to use this flow.
     * -
     * - `NEW_PASSWORD_REQUIRED`: For users who are required to change their passwords after successful first login. Respond
     *   to this challenge with `NEW_PASSWORD` and any required attributes that Amazon Cognito returned in the
     *   `requiredAttributes` parameter. You can also set values for attributes that aren't required by your user pool and
     *   that your app client can write. For more information, see AdminRespondToAuthChallenge [^1].
     *
     *   > In a `NEW_PASSWORD_REQUIRED` challenge response, you can't modify a required attribute that already has a value.
     *   > In `AdminRespondToAuthChallenge`, set a value for any keys that Amazon Cognito returned in the
     *   > `requiredAttributes` parameter, then use the `AdminUpdateUserAttributes` API operation to modify the value of any
     *   > additional attributes.
     *
     * - `MFA_SETUP`: For users who are required to set up an MFA factor before they can sign in. The MFA types activated
     *   for the user pool will be listed in the challenge parameters `MFA_CAN_SETUP` value.
     *
     *   To set up software token MFA, use the session returned here from `InitiateAuth` as an input to
     *   `AssociateSoftwareToken`, and use the session returned by `VerifySoftwareToken` as an input to
     *   `RespondToAuthChallenge` with challenge name `MFA_SETUP` to complete sign-in. To set up SMS MFA, users will need
     *   help from an administrator to add a phone number to their account and then call `InitiateAuth` again to restart
     *   sign-in.
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminRespondToAuthChallenge.html
     */
    private $challengeName;

    /**
     * The session that should be passed both ways in challenge-response calls to the service. If `AdminInitiateAuth` or
     * `AdminRespondToAuthChallenge` API call determines that the caller must pass another challenge, they return a session
     * with other challenge parameters. This session should be passed as it is to the next `AdminRespondToAuthChallenge` API
     * call.
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
     */
    private $challengeParameters;

    /**
     * The result of the authentication response. This is only returned if the caller doesn't need to pass another
     * challenge. If the caller does need to pass another challenge before it gets tokens, `ChallengeName`,
     * `ChallengeParameters`, and `Session` are returned.
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
