<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the request to disable the user as an administrator.
 */
final class AdminDisableUserRequest extends Input
{
    /**
     * The user pool ID for the user pool where you want to disable the user.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * The user name of the user you want to disable.
     *
     * @required
     *
     * @var string|null
     */
    private $username;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->username = $input['Username'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getUserPoolId(): ?string
    {
        return $this->userPoolId;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminDisableUser',
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

    public function setUserPoolId(?string $value): self
    {
        $this->userPoolId = $value;

        return $this;
    }

    public function setUsername(?string $value): self
    {
        $this->username = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->userPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;

        return $payload;
    }
}
