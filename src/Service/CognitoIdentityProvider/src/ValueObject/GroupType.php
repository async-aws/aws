<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The group type.
 */
final class GroupType
{
    /**
     * The name of the group.
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The user pool ID for the user pool.
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
     * precedence over groups with higher ornull `Precedence` values. If a user belongs to two or more groups, it is the
     * group with the lowest precedence value whose role ARN is given in the user's tokens for the `cognito:roles` and
     * `cognito:preferred_role` claims.
     *
     * Two groups can have the same `Precedence` value. If this happens, neither group takes precedence over the other. If
     * two groups with the same `Precedence` have the same role ARN, that role is used in the `cognito:preferred_role` claim
     * in tokens for users in each group. If the two groups have different role ARNs, the `cognito:preferred_role` claim
     * isn't set in users' tokens.
     *
     * The default `Precedence` value is null.
     *
     * @var int|null
     */
    private $precedence;

    /**
     * The date and time when the item was modified. Amazon Cognito returns this timestamp in UNIX epoch time format. Your
     * SDK might render the output in a human-readable format like ISO 8601 or a Java `Date` object.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModifiedDate;

    /**
     * The date and time when the item was created. Amazon Cognito returns this timestamp in UNIX epoch time format. Your
     * SDK might render the output in a human-readable format like ISO 8601 or a Java `Date` object.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * @param array{
     *   GroupName?: null|string,
     *   UserPoolId?: null|string,
     *   Description?: null|string,
     *   RoleArn?: null|string,
     *   Precedence?: null|int,
     *   LastModifiedDate?: null|\DateTimeImmutable,
     *   CreationDate?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->groupName = $input['GroupName'] ?? null;
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->roleArn = $input['RoleArn'] ?? null;
        $this->precedence = $input['Precedence'] ?? null;
        $this->lastModifiedDate = $input['LastModifiedDate'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
    }

    /**
     * @param array{
     *   GroupName?: null|string,
     *   UserPoolId?: null|string,
     *   Description?: null|string,
     *   RoleArn?: null|string,
     *   Precedence?: null|int,
     *   LastModifiedDate?: null|\DateTimeImmutable,
     *   CreationDate?: null|\DateTimeImmutable,
     * }|GroupType $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getLastModifiedDate(): ?\DateTimeImmutable
    {
        return $this->lastModifiedDate;
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
}
