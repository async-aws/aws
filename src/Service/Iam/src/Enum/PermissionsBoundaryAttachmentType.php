<?php

namespace AsyncAws\Iam\Enum;

final class PermissionsBoundaryAttachmentType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const PERMISSIONS_BOUNDARY_POLICY = 'PermissionsBoundaryPolicy';

    public static function exists(string $value): bool
    {
        return isset([
            self::PERMISSIONS_BOUNDARY_POLICY => true,
        ][$value]);
    }
}
