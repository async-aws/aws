<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AssociateSoftwareTokenRequest extends Input
{
    /**
     * A valid access token that Amazon Cognito issued to the currently signed-in user. Must include a scope claim for
     * `aws.cognito.signin.user.admin`.
     *
     * You can provide either an access token or a session ID in the request.
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * The session identifier that maintains the state of authentication requests and challenge responses. In
     * `AssociateSoftwareToken`, this is the session ID from a successful sign-in. You can provide either an access token or
     * a session ID in the request.
     *
     * @var string|null
     */
    private $session;

    /**
     * @param array{
     *   AccessToken?: string|null,
     *   Session?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->accessToken = $input['AccessToken'] ?? null;
        $this->session = $input['Session'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   AccessToken?: string|null,
     *   Session?: string|null,
     *   '@region'?: string|null,
     * }|AssociateSoftwareTokenRequest $input
     */
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
