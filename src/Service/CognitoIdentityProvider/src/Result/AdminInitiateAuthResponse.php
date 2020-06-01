<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AuthenticationResultType;
use AsyncAws\CognitoIdentityProvider\ValueObject\NewDeviceMetadataType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class AdminInitiateAuthResponse extends Result
{
    /**
     * The name of the challenge which you are responding to with this call. This is returned to you in the
     * `AdminInitiateAuth` response if you need to pass another challenge.
     */
    private $ChallengeName;

    /**
     * The session which should be passed both ways in challenge-response calls to the service. If `AdminInitiateAuth` or
     * `AdminRespondToAuthChallenge` API call determines that the caller needs to go through another challenge, they return
     * a session with other challenge parameters. This session should be passed as it is to the next
     * `AdminRespondToAuthChallenge` API call.
     */
    private $Session;

    /**
     * The challenge parameters. These are returned to you in the `AdminInitiateAuth` response if you need to pass another
     * challenge. The responses in this parameter should be used to compute inputs to the next call
     * (`AdminRespondToAuthChallenge`).
     */
    private $ChallengeParameters = [];

    /**
     * The result of the authentication response. This is only returned if the caller does not need to pass another
     * challenge. If the caller does need to pass another challenge before it gets tokens, `ChallengeName`,
     * `ChallengeParameters`, and `Session` are returned.
     */
    private $AuthenticationResult;

    public function getAuthenticationResult(): ?AuthenticationResultType
    {
        $this->initialize();

        return $this->AuthenticationResult;
    }

    /**
     * @return ChallengeNameType::*|null
     */
    public function getChallengeName(): ?string
    {
        $this->initialize();

        return $this->ChallengeName;
    }

    /**
     * @return array<string, string>
     */
    public function getChallengeParameters(): array
    {
        $this->initialize();

        return $this->ChallengeParameters;
    }

    public function getSession(): ?string
    {
        $this->initialize();

        return $this->Session;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->ChallengeName = isset($data['ChallengeName']) ? (string) $data['ChallengeName'] : null;
        $this->Session = isset($data['Session']) ? (string) $data['Session'] : null;
        $this->ChallengeParameters = empty($data['ChallengeParameters']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $name => $value) {
                $items[(string) $name] = (string) $value;
            }

            return $items;
        })($data['ChallengeParameters']);
        $this->AuthenticationResult = empty($data['AuthenticationResult']) ? null : new AuthenticationResultType([
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
}
