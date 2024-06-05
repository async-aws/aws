<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AdminAddUserToGroupRequest extends Input
{
    /**
     * The user pool ID for the user pool.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * The username of the user that you want to query or modify. The value of this parameter is typically your user's
     * username, but it can be any of their alias attributes. If `username` isn't an alias attribute in your user pool, this
     * value must be the `sub` of a local user or the username of a user from a third-party IdP.
     *
     * @required
     *
     * @var string|null
     */
    private $username;

    /**
     * The name of the group that you want to add your user to.
     *
     * @required
     *
     * @var string|null
     */
    private $groupName;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   GroupName?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->username = $input['Username'] ?? null;
        $this->groupName = $input['GroupName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   GroupName?: string,
     *   '@region'?: string|null,
     * }|AdminAddUserToGroupRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
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
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminAddUserToGroup',
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

    public function setGroupName(?string $value): self
    {
        $this->groupName = $value;

        return $this;
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
        if (null === $v = $this->groupName) {
            throw new InvalidArgument(sprintf('Missing parameter "GroupName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['GroupName'] = $v;

        return $payload;
    }
}
