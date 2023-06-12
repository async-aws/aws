<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AuthenticationResultType;
use AsyncAws\CognitoIdentityProvider\ValueObject\NewDeviceMetadataType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Initiates the authentication response.
 */
class InitiateAuthResponse extends Result
{
    /**
     * The name of the challenge that you're responding to with this call. This name is returned in the `AdminInitiateAuth`
     * response if you must pass another challenge.
     *
     * Valid values include the following:
     *
     * > All of the following challenges require `USERNAME` and `SECRET_HASH` (if applicable) in the parameters.
     *
     * - `SMS_MFA`: Next challenge is to supply an `SMS_MFA_CODE`, delivered via SMS.
     * - `PASSWORD_VERIFIER`: Next challenge is to supply `PASSWORD_CLAIM_SIGNATURE`, `PASSWORD_CLAIM_SECRET_BLOCK`, and
     *   `TIMESTAMP` after the client-side SRP calculations.
     * - `CUSTOM_CHALLENGE`: This is returned if your custom authentication flow determines that the user should pass
     *   another challenge before tokens are issued.
     * - `DEVICE_SRP_AUTH`: If device tracking was activated on your user pool and the previous challenges were passed, this
     *   challenge is returned so that Amazon Cognito can start tracking this device.
     * - `DEVICE_PASSWORD_VERIFIER`: Similar to `PASSWORD_VERIFIER`, but for devices only.
     * - `NEW_PASSWORD_REQUIRED`: For users who are required to change their passwords after successful first login.
     *
     *   Respond to this challenge with `NEW_PASSWORD` and any required attributes that Amazon Cognito returned in the
     *   `requiredAttributes` parameter. You can also set values for attributes that aren't required by your user pool and
     *   that your app client can write. For more information, see RespondToAuthChallenge [^1].
     *
     *   > In a `NEW_PASSWORD_REQUIRED` challenge response, you can't modify a required attribute that already has a value.
     *   > In `RespondToAuthChallenge`, set a value for any keys that Amazon Cognito returned in the `requiredAttributes`
     *   > parameter, then use the `UpdateUserAttributes` API operation to modify the value of any additional attributes.
     *
     * - `MFA_SETUP`: For users who are required to setup an MFA factor before they can sign in. The MFA types activated for
     *   the user pool will be listed in the challenge parameters `MFA_CAN_SETUP` value.
     *
     *   To set up software token MFA, use the session returned here from `InitiateAuth` as an input to
     *   `AssociateSoftwareToken`. Use the session returned by `VerifySoftwareToken` as an input to `RespondToAuthChallenge`
     *   with challenge name `MFA_SETUP` to complete sign-in. To set up SMS MFA, an administrator should help the user to
     *   add a phone number to their account, and then the user should call `InitiateAuth` again to restart sign-in.
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_RespondToAuthChallenge.html
     */
    private $challengeName;

    /**
     * The session that should pass both ways in challenge-response calls to the service. If the caller must pass another
     * challenge, they return a session with other challenge parameters. This session should be passed as it is to the next
     * `RespondToAuthChallenge` API call.
     */
    private $session;

    /**
     * The challenge parameters. These are returned in the `InitiateAuth` response if you must pass another challenge. The
     * responses in this parameter should be used to compute inputs to the next call (`RespondToAuthChallenge`).
     *
     * All challenges require `USERNAME` and `SECRET_HASH` (if applicable).
     */
    private $challengeParameters;

    /**
     * The result of the authentication response. This result is only returned if the caller doesn't need to pass another
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
