<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Iam\Enum\PermissionsBoundaryAttachmentType;

final class AttachedPermissionsBoundary
{
    /**
     * The permissions boundary usage type that indicates what type of IAM resource is used as the permissions boundary for
     * an entity. This data type can only have a value of `Policy`.
     */
    private $PermissionsBoundaryType;

    /**
     * The ARN of the policy used to set the permissions boundary for the user or role.
     */
    private $PermissionsBoundaryArn;

    /**
     * @param array{
     *   PermissionsBoundaryType?: null|PermissionsBoundaryAttachmentType::*,
     *   PermissionsBoundaryArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->PermissionsBoundaryType = $input['PermissionsBoundaryType'] ?? null;
        $this->PermissionsBoundaryArn = $input['PermissionsBoundaryArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPermissionsBoundaryArn(): ?string
    {
        return $this->PermissionsBoundaryArn;
    }

    /**
     * @return PermissionsBoundaryAttachmentType::*|null
     */
    public function getPermissionsBoundaryType(): ?string
    {
        return $this->PermissionsBoundaryType;
    }
}
