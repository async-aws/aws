<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AdminDeleteUserRequest extends Input
{
    /**
     * The user pool ID for the user pool where you want to delete the user.
     *
     * @required
     *
     * @var string|null
     */
    private $UserPoolId;

    /**
     * The user name of the user you wish to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $Username;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->UserPoolId = $input['UserPoolId'] ?? null;
        $this->Username = $input['Username'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getUserPoolId(): ?string
    {
        return $this->UserPoolId;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminDeleteUser',
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

    public function setUserPoolId(?string $value): self
    {
        $this->UserPoolId = $value;

        return $this;
    }

    public function setUsername(?string $value): self
    {
        $this->Username = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->UserPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->Username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;

        return $payload;
    }
}
