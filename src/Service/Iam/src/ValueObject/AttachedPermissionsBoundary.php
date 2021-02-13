<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Iam\Enum\PermissionsBoundaryAttachmentType;

/**
 * For more information about permissions boundaries, see Permissions boundaries for IAM identities  in the *IAM User
 * Guide*.
 *
 * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/access_policies_boundaries.html
 */
final class AttachedPermissionsBoundary
{
    /**
     * The permissions boundary usage type that indicates what type of IAM resource is used as the permissions boundary for
     * an entity. This data type can only have a value of `Policy`.
     */
    private $permissionsBoundaryType;

    /**
     * The ARN of the policy used to set the permissions boundary for the user or role.
     */
    private $permissionsBoundaryArn;

    /**
     * @param array{
     *   PermissionsBoundaryType?: null|PermissionsBoundaryAttachmentType::*,
     *   PermissionsBoundaryArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->permissionsBoundaryType = $input['PermissionsBoundaryType'] ?? null;
        $this->permissionsBoundaryArn = $input['PermissionsBoundaryArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPermissionsBoundaryArn(): ?string
    {
        return $this->permissionsBoundaryArn;
    }

    /**
     * @return PermissionsBoundaryAttachmentType::*|null
     */
    public function getPermissionsBoundaryType(): ?string
    {
        return $this->permissionsBoundaryType;
    }
}
