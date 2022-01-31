<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AuthenticationResultType;
use AsyncAws\CognitoIdentityProvider\ValueObject\NewDeviceMetadataType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The response to respond to the authentication challenge.
 */
class RespondToAuthChallengeResponse extends Result
{
    /**
     * The challenge name. For more information, see InitiateAuth.
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_InitiateAuth.html
     */
    private $challengeName;

    /**
     * The session that should be passed both ways in challenge-response calls to the service. If the caller must pass
     * another challenge, they return a session with other challenge parameters. This session should be passed as it is to
     * the next `RespondToAuthChallenge` API call.
     */
    private $session;

    /**
     * The challenge parameters. For more information, see InitiateAuth.
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_InitiateAuth.html
     */
    private $challengeParameters;

    /**
     * The result returned by the server in response to the request to respond to the authentication challenge.
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
        $this->authenticationResult = empty($data['AuthenticationResult']) ? null : new AuthenticationResultType([
            'AccessToken' => isset($data['AuthenticationResult']['AccessToken']) ? (string) $data['AuthenticationResult']['AccessToken'] : null,
            'ExpiresIn' => isset($data['AuthenticationResult']['ExpiresIn']) ? (int) $data['AuthenticationResult']['ExpiresIn'] : null,
            'TokenType' => isset($data['AuthenticationResult']['TokenType']) ? (string) $data['AuthenticationResult']['TokenType'] : null,
            'RefreshToken' => isset($data['AuthenticationResult']['RefreshToken']) ? (string) $data['AuthenticationResult']['RefreshToken'] : null,
            'IdToken' => isset($data['AuthenticationResult']['IdToken']) ? (string) $data['AuthenticationResult']['IdToken'] : null,
            'NewDeviceMetadata' => empty($data['AuthenticationResult']['NewDeviceMetadata']) ? null : new NewDeviceMetadataType([
                'DeviceKey' => isset($data['AuthenticationResult']['NewDeviceMetadata']['DeviceKey']) ? (string) $data['AuthenticationResult']['NewDeviceMetadata']['DeviceKey'] : null,
                'DeviceGroupKey' => isset($data['AuthenticationResult']['NewDeviceMetadata']['DeviceGroupKey']) ? (string) $data['AuthenticationResult']['NewDeviceMetadata']['DeviceGroupKey'] : null,
            ]),
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
}
