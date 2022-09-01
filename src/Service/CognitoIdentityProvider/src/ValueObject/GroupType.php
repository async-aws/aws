<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The group type.
 */
final class GroupType
{
    /**
     * The name of the group.
     */
    private $groupName;

    /**
     * The user pool ID for the user pool.
     */
    private $userPoolId;

    /**
     * A string containing the description of the group.
     */
    private $description;

    /**
     * The role Amazon Resource Name (ARN) for the group.
     */
    private $roleArn;

    /**
     * A non-negative integer value that specifies the precedence of this group relative to the other groups that a user can
     * belong to in the user pool. Zero is the highest precedence value. Groups with lower `Precedence` values take
     * precedence over groups with higher ornull `Precedence` values. If a user belongs to two or more groups, it is the
     * group with the lowest precedence value whose role ARN is given in the user's tokens for the `cognito:roles` and
     * `cognito:preferred_role` claims.
     */
    private $precedence;

    /**
     * The date the group was last modified.
     */
    private $lastModifiedDate;

    /**
     * The date the group was created.
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
