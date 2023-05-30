<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateGroupRequest extends Input
{
    /**
     * The name of the group. Must be unique.
     *
     * @required
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The user pool ID for the user pool.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * A string containing the description of the group.
     *
     * @var string|null
     */
    private $description;

    /**
     * The role Amazon Resource Name (ARN) for the group.
     *
     * @var string|null
     */
    private $roleArn;

    /**
     * A non-negative integer value that specifies the precedence of this group relative to the other groups that a user can
     * belong to in the user pool. Zero is the highest precedence value. Groups with lower `Precedence` values take
     * precedence over groups with higher or null `Precedence` values. If a user belongs to two or more groups, it is the
     * group with the lowest precedence value whose role ARN is given in the user's tokens for the `cognito:roles` and
     * `cognito:preferred_role` claims.
     *
     * Two groups can have the same `Precedence` value. If this happens, neither group takes precedence over the other. If
     * two groups with the same `Precedence` have the same role ARN, that role is used in the `cognito:preferred_role` claim
     * in tokens for users in each group. If the two groups have different role ARNs, the `cognito:preferred_role` claim
     * isn't set in users' tokens.
     *
     * The default `Precedence` value is null. The maximum `Precedence` value is `2^31-1`.
     *
     * @var int|null
     */
    private $precedence;

    /**
     * @param array{
     *   GroupName?: string,
     *   UserPoolId?: string,
     *   Description?: string,
     *   RoleArn?: string,
     *   Precedence?: int,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->groupName = $input['GroupName'] ?? null;
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->roleArn = $input['RoleArn'] ?? null;
        $this->precedence = $input['Precedence'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getPrecedence(): ?int
    {
        return $this->precedence;
    }

    public function getRoleArn(): ?string
    {
        return $this->roleArn;
    }

    public function getUserPoolId(): ?string
    {
        return $this->userPoolId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.CreateGroup',
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

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setGroupName(?string $value): self
    {
        $this->groupName = $value;

        return $this;
    }

    public function setPrecedence(?int $value): self
    {
        $this->precedence = $value;

        return $this;
    }

    public function setRoleArn(?string $value): self
    {
        $this->roleArn = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->userPoolId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->groupName) {
            throw new InvalidArgument(sprintf('Missing parameter "GroupName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['GroupName'] = $v;
        if (null === $v = $this->userPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null !== $v = $this->roleArn) {
            $payload['RoleArn'] = $v;
        }
        if (null !== $v = $this->precedence) {
            $payload['Precedence'] = $v;
        }

        return $payload;
    }
}
