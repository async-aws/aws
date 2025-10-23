<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class VerifySoftwareTokenRequest extends Input
{
    /**
     * A valid access token that Amazon Cognito issued to the currently signed-in user. Must include a scope claim for
     * `aws.cognito.signin.user.admin`.
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * The session ID from an `AssociateSoftwareToken` request.
     *
     * @var string|null
     */
    private $session;

    /**
     * A TOTP that the user generated in their configured authenticator app.
     *
     * @required
     *
     * @var string|null
     */
    private $userCode;

    /**
     * A friendly name for the device that's running the TOTP authenticator.
     *
     * @var string|null
     */
    private $friendlyDeviceName;

    /**
     * @param array{
     *   AccessToken?: string|null,
     *   Session?: string|null,
     *   UserCode?: string,
     *   FriendlyDeviceName?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->accessToken = $input['AccessToken'] ?? null;
        $this->session = $input['Session'] ?? null;
        $this->userCode = $input['UserCode'] ?? null;
        $this->friendlyDeviceName = $input['FriendlyDeviceName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   AccessToken?: string|null,
     *   Session?: string|null,
     *   UserCode?: string,
     *   FriendlyDeviceName?: string|null,
     *   '@region'?: string|null,
     * }|VerifySoftwareTokenRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getFriendlyDeviceName(): ?string
    {
        return $this->friendlyDeviceName;
    }

    public function getSession(): ?string
    {
        return $this->session;
    }

    public function getUserCode(): ?string
    {
        return $this->userCode;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.VerifySoftwareToken',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccessToken(?string $value): self
    {
        $this->accessToken = $value;

        return $this;
    }

    public function setFriendlyDeviceName(?string $value): self
    {
        $this->friendlyDeviceName = $value;

        return $this;
    }

    public function setSession(?string $value): self
    {
        $this->session = $value;

        return $this;
    }

    public function setUserCode(?string $value): self
    {
        $this->userCode = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->accessToken) {
            $payload['AccessToken'] = $v;
        }
        if (null !== $v = $this->session) {
            $payload['Session'] = $v;
        }
        if (null === $v = $this->userCode) {
            throw new InvalidArgument(\sprintf('Missing parameter "UserCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserCode'] = $v;
        if (null !== $v = $this->friendlyDeviceName) {
            $payload['FriendlyDeviceName'] = $v;
        }

        return $payload;
    }
}
