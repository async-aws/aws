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
     * Possible challenges include the following:
     *
     * > All of the following challenges require `USERNAME` and, when the app client has a client secret, `SECRET_HASH` in
     * > the parameters.
     *
     * - `WEB_AUTHN`: Respond to the challenge with the results of a successful authentication with a WebAuthn
     *   authenticator, or passkey. Examples of WebAuthn authenticators include biometric devices and security keys.
     * - `PASSWORD`: Respond with `USER_PASSWORD_AUTH` parameters: `USERNAME` (required), `PASSWORD` (required),
     *   `SECRET_HASH` (required if the app client is configured with a client secret), `DEVICE_KEY`.
     * - `PASSWORD_SRP`: Respond with `USER_SRP_AUTH` parameters: `USERNAME` (required), `SRP_A` (required), `SECRET_HASH`
     *   (required if the app client is configured with a client secret), `DEVICE_KEY`.
     * - `SELECT_CHALLENGE`: Respond to the challenge with `USERNAME` and an `ANSWER` that matches one of the challenge
     *   types in the `AvailableChallenges` response parameter.
     * - `SMS_MFA`: Respond with an `SMS_MFA_CODE` that your user pool delivered in an SMS message.
     * - `EMAIL_OTP`: Respond with an `EMAIL_OTP_CODE` that your user pool delivered in an email message.
     * - `PASSWORD_VERIFIER`: Respond with `PASSWORD_CLAIM_SIGNATURE`, `PASSWORD_CLAIM_SECRET_BLOCK`, and `TIMESTAMP` after
     *   client-side SRP calculations.
     * - `CUSTOM_CHALLENGE`: This is returned if your custom authentication flow determines that the user should pass
     *   another challenge before tokens are issued. The parameters of the challenge are determined by your Lambda function.
     * - `DEVICE_SRP_AUTH`: Respond with the initial parameters of device SRP authentication. For more information, see
     *   Signing in with a device [^1].
     * - `DEVICE_PASSWORD_VERIFIER`: Respond with `PASSWORD_CLAIM_SIGNATURE`, `PASSWORD_CLAIM_SECRET_BLOCK`, and `TIMESTAMP`
     *   after client-side SRP calculations. For more information, see Signing in with a device [^2].
     * - `NEW_PASSWORD_REQUIRED`: For users who are required to change their passwords after successful first login. Respond
     *   to this challenge with `NEW_PASSWORD` and any required attributes that Amazon Cognito returned in the
     *   `requiredAttributes` parameter. You can also set values for attributes that aren't required by your user pool and
     *   that your app client can write.
     *
     *   Amazon Cognito only returns this challenge for users who have temporary passwords. When you create passwordless
     *   users, you must provide values for all required attributes.
     *
     *   > In a `NEW_PASSWORD_REQUIRED` challenge response, you can't modify a required attribute that already has a value.
     *   > In `AdminRespondToAuthChallenge` or `RespondToAuthChallenge`, set a value for any keys that Amazon Cognito
     *   > returned in the `requiredAttributes` parameter, then use the `AdminUpdateUserAttributes` or
     *   > `UpdateUserAttributes` API operation to modify the value of any additional attributes.
     *
     * - `MFA_SETUP`: For users who are required to setup an MFA factor before they can sign in. The MFA types activated for
     *   the user pool will be listed in the challenge parameters `MFAS_CAN_SETUP` value.
     *
     *   To set up time-based one-time password (TOTP) MFA, use the session returned in this challenge from `InitiateAuth`
     *   or `AdminInitiateAuth` as an input to `AssociateSoftwareToken`. Then, use the session returned by
     *   `VerifySoftwareToken` as an input to `RespondToAuthChallenge` or `AdminRespondToAuthChallenge` with challenge name
     *   `MFA_SETUP` to complete sign-in.
     *
     *   To set up SMS or email MFA, collect a `phone_number` or `email` attribute for the user. Then restart the
     *   authentication flow with an `InitiateAuth` or `AdminInitiateAuth` request.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html#user-pools-remembered-devices-signing-in-with-a-device
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html#user-pools-remembered-devices-signing-in-with-a-device
     *
     * @var ChallengeNameType::*|string|null
     */
    private $challengeName;

    /**
     * The session that must be passed to challenge-response requests. If an `AdminInitiateAuth` or
     * `AdminRespondToAuthChallenge` API request results in another authentication challenge, Amazon Cognito returns a
     * session ID and the parameters of the next challenge. Pass this session ID in the `Session` parameter of
     * `AdminRespondToAuthChallenge`.
     *
     * @var string|null
     */
    private $session;

    /**
     * The parameters of an authentication challenge. Amazon Cognito returns challenge parameters as a guide to the
     * responses your user or application must provide for the returned `ChallengeName`. Calculate responses to the
     * challenge parameters and pass them in the `ChallengeParameters` of `AdminRespondToAuthChallenge`.
     *
     * All challenges require `USERNAME` and, when the app client has a client secret, `SECRET_HASH`.
     *
     * In SRP challenges, Amazon Cognito returns the `username` attribute in `USER_ID_FOR_SRP` instead of any email address,
     * preferred username, or phone number alias that you might have specified in your `AdminInitiateAuth` request. You must
     * use the username and not an alias in the `ChallengeResponses` of your challenge response.
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

    /**
     * This response parameter lists the available authentication challenges that users can select from in choice-based
     * authentication [^1]. For example, they might be able to choose between passkey authentication, a one-time password
     * from an SMS message, and a traditional password.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/authentication-flows-selection-sdk.html#authentication-flows-selection-choice
     *
     * @var list<ChallengeNameType::*|string>
     */
    private $availableChallenges;

    public function getAuthenticationResult(): ?AuthenticationResultType
    {
        $this->initialize();

        return $this->authenticationResult;
    }

    /**
     * @return list<ChallengeNameType::*|string>
     */
    public function getAvailableChallenges(): array
    {
        $this->initialize();

        return $this->availableChallenges;
    }

    /**
     * @return ChallengeNameType::*|string|null
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
        $this->availableChallenges = empty($data['AvailableChallenges']) ? [] : $this->populateResultAvailableChallengeListType($data['AvailableChallenges']);
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
     * @return list<ChallengeNameType::*|string>
     */
    private function populateResultAvailableChallengeListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
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
