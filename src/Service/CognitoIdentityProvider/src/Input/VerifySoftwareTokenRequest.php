<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class VerifySoftwareTokenRequest extends Input
{
    /**
     * The access token.
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * The session which should be passed both ways in challenge-response calls to the service.
     *
     * @var string|null
     */
    private $session;

    /**
     * The one time password computed using the secret code returned by AssociateSoftwareToken".
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AssociateSoftwareToken.html
     * @required
     *
     * @var string|null
     */
    private $userCode;

    /**
     * The friendly device name.
     *
     * @var string|null
     */
    private $friendlyDeviceName;

    /**
     * @param array{
     *   AccessToken?: string,
     *   Session?: string,
     *   UserCode?: string,
     *   FriendlyDeviceName?: string,
     *   @region?: string,
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
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

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
            throw new InvalidArgument(sprintf('Missing parameter "UserCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserCode'] = $v;
        if (null !== $v = $this->friendlyDeviceName) {
            $payload['FriendlyDeviceName'] = $v;
        }

        return $payload;
    }
}
