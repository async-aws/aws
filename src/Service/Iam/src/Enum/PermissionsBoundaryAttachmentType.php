<?php

namespace AsyncAws\Iam\Enum;

/**
 * The permissions boundary usage type that indicates what type of IAM resource is used as the permissions boundary for
 * an entity. This data type can only have a value of `Policy`.
 */
final class PermissionsBoundaryAttachmentType
{
    public const PERMISSIONS_BOUNDARY_POLICY = 'PermissionsBoundaryPolicy';

    public static function exists(string $value): bool
    {
        return isset([
            self::PERMISSIONS_BOUNDARY_POLICY => true,
        ][$value]);
    }
}
