<?php

namespace AsyncAws\Iam\Enum;

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
