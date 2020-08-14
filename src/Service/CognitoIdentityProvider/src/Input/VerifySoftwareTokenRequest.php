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
    private $AccessToken;

    /**
     * The session which should be passed both ways in challenge-response calls to the service.
     *
     * @var string|null
     */
    private $Session;

    /**
     * The one time password computed using the secret code returned by AssociateSoftwareToken".
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AssociateSoftwareToken.html
     * @required
     *
     * @var string|null
     */
    private $UserCode;

    /**
     * The friendly device name.
     *
     * @var string|null
     */
    private $FriendlyDeviceName;

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
        $this->AccessToken = $input['AccessToken'] ?? null;
        $this->Session = $input['Session'] ?? null;
        $this->UserCode = $input['UserCode'] ?? null;
        $this->FriendlyDeviceName = $input['FriendlyDeviceName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->AccessToken;
    }

    public function getFriendlyDeviceName(): ?string
    {
        return $this->FriendlyDeviceName;
    }

    public function getSession(): ?string
    {
        return $this->Session;
    }

    public function getUserCode(): ?string
    {
        return $this->UserCode;
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
        $this->AccessToken = $value;

        return $this;
    }

    public function setFriendlyDeviceName(?string $value): self
    {
        $this->FriendlyDeviceName = $value;

        return $this;
    }

    public function setSession(?string $value): self
    {
        $this->Session = $value;

        return $this;
    }

    public function setUserCode(?string $value): self
    {
        $this->UserCode = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->AccessToken) {
            $payload['AccessToken'] = $v;
        }
        if (null !== $v = $this->Session) {
            $payload['Session'] = $v;
        }
        if (null === $v = $this->UserCode) {
            throw new InvalidArgument(sprintf('Missing parameter "UserCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserCode'] = $v;
        if (null !== $v = $this->FriendlyDeviceName) {
            $payload['FriendlyDeviceName'] = $v;
        }

        return $payload;
    }
}
