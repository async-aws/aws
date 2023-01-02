<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AssociateSoftwareTokenRequest extends Input
{
    /**
     * A valid access token that Amazon Cognito issued to the user whose software token you want to generate.
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * The session that should be passed both ways in challenge-response calls to the service. This allows authentication of
     * the user as part of the MFA setup process.
     *
     * @var string|null
     */
    private $session;

    /**
     * @param array{
     *   AccessToken?: string,
     *   Session?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->accessToken = $input['AccessToken'] ?? null;
        $this->session = $input['Session'] ?? null;
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

    public function getSession(): ?string
    {
        return $this->session;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AssociateSoftwareToken',
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

    public function setSession(?string $value): self
    {
        $this->session = $value;

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

        return $payload;
    }
}
