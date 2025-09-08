<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Iam\Enum\PermissionsBoundaryAttachmentType;

/**
 * Contains information about an attached permissions boundary.
 *
 * An attached permissions boundary is a managed policy that has been attached to a user or role to set the permissions
 * boundary.
 *
 * For more information about permissions boundaries, see Permissions boundaries for IAM identities [^1] in the *IAM
 * User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/access_policies_boundaries.html
 */
final class AttachedPermissionsBoundary
{
    /**
     * The permissions boundary usage type that indicates what type of IAM resource is used as the permissions boundary for
     * an entity. This data type can only have a value of `Policy`.
     *
     * @var PermissionsBoundaryAttachmentType::*|null
     */
    private $permissionsBoundaryType;

    /**
     * The ARN of the policy used to set the permissions boundary for the user or role.
     *
     * @var string|null
     */
    private $permissionsBoundaryArn;

    /**
     * @param array{
     *   PermissionsBoundaryType?: PermissionsBoundaryAttachmentType::*|null,
     *   PermissionsBoundaryArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->permissionsBoundaryType = $input['PermissionsBoundaryType'] ?? null;
        $this->permissionsBoundaryArn = $input['PermissionsBoundaryArn'] ?? null;
    }

    /**
     * @param array{
     *   PermissionsBoundaryType?: PermissionsBoundaryAttachmentType::*|null,
     *   PermissionsBoundaryArn?: string|null,
     * }|AttachedPermissionsBoundary $input
     */
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
