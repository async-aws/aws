<?php

namespace AsyncAws\Iam\Enum;

final class PermissionsBoundaryAttachmentType
{
    public const PERMISSIONS_BOUNDARY_POLICY = 'PermissionsBoundaryPolicy';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PERMISSIONS_BOUNDARY_POLICY => true,
        ][$value]);
    }
}
