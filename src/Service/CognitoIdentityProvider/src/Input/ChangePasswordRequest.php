<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the request to change a user password.
 */
final class ChangePasswordRequest extends Input
{
    /**
     * The old password.
     *
     * @required
     *
     * @var string|null
     */
    private $previousPassword;

    /**
     * The new password.
     *
     * @required
     *
     * @var string|null
     */
    private $proposedPassword;

    /**
     * A valid access token that Amazon Cognito issued to the user whose password you want to change.
     *
     * @required
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * @param array{
     *   PreviousPassword?: string,
     *   ProposedPassword?: string,
     *   AccessToken?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->previousPassword = $input['PreviousPassword'] ?? null;
        $this->proposedPassword = $input['ProposedPassword'] ?? null;
        $this->accessToken = $input['AccessToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   PreviousPassword?: string,
     *   ProposedPassword?: string,
     *   AccessToken?: string,
     *   '@region'?: string|null,
     * }|ChangePasswordRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getPreviousPassword(): ?string
    {
        return $this->previousPassword;
    }

    public function getProposedPassword(): ?string
    {
        return $this->proposedPassword;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'Accept' => 'application/json',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.ChangePassword',
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

    public function setPreviousPassword(?string $value): self
    {
        $this->previousPassword = $value;

        return $this;
    }

    public function setProposedPassword(?string $value): self
    {
        $this->proposedPassword = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->previousPassword) {
            throw new InvalidArgument(sprintf('Missing parameter "PreviousPassword" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['PreviousPassword'] = $v;
        if (null === $v = $this->proposedPassword) {
            throw new InvalidArgument(sprintf('Missing parameter "ProposedPassword" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ProposedPassword'] = $v;
        if (null === $v = $this->accessToken) {
            throw new InvalidArgument(sprintf('Missing parameter "AccessToken" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AccessToken'] = $v;

        return $payload;
    }
}
