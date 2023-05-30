<?php

namespace AsyncAws\Lambda\Enum;

final class LastUpdateStatusReasonCode
{
    public const DISABLED_KMSKEY = 'DisabledKMSKey';
    public const EFSIOERROR = 'EFSIOError';
    public const EFSMOUNT_CONNECTIVITY_ERROR = 'EFSMountConnectivityError';
    public const EFSMOUNT_FAILURE = 'EFSMountFailure';
    public const EFSMOUNT_TIMEOUT = 'EFSMountTimeout';
    public const ENI_LIMIT_EXCEEDED = 'EniLimitExceeded';
    public const FUNCTION_ERROR = 'FunctionError';
    public const IMAGE_ACCESS_DENIED = 'ImageAccessDenied';
    public const IMAGE_DELETED = 'ImageDeleted';
    public const INSUFFICIENT_ROLE_PERMISSIONS = 'InsufficientRolePermissions';
    public const INTERNAL_ERROR = 'InternalError';
    public const INVALID_CONFIGURATION = 'InvalidConfiguration';
    public const INVALID_IMAGE = 'InvalidImage';
    public const INVALID_RUNTIME = 'InvalidRuntime';
    public const INVALID_SECURITY_GROUP = 'InvalidSecurityGroup';
    public const INVALID_STATE_KMSKEY = 'InvalidStateKMSKey';
    public const INVALID_SUBNET = 'InvalidSubnet';
    public const INVALID_ZIP_FILE_EXCEPTION = 'InvalidZipFileException';
    public const KMSKEY_ACCESS_DENIED = 'KMSKeyAccessDenied';
    public const KMSKEY_NOT_FOUND = 'KMSKeyNotFound';
    public const SUBNET_OUT_OF_IPADDRESSES = 'SubnetOutOfIPAddresses';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED_KMSKEY => true,
            self::EFSIOERROR => true,
            self::EFSMOUNT_CONNECTIVITY_ERROR => true,
            self::EFSMOUNT_FAILURE => true,
            self::EFSMOUNT_TIMEOUT => true,
            self::ENI_LIMIT_EXCEEDED => true,
            self::FUNCTION_ERROR => true,
            self::IMAGE_ACCESS_DENIED => true,
            self::IMAGE_DELETED => true,
            self::INSUFFICIENT_ROLE_PERMISSIONS => true,
            self::INTERNAL_ERROR => true,
            self::INVALID_CONFIGURATION => true,
            self::INVALID_IMAGE => true,
            self::INVALID_RUNTIME => true,
            self::INVALID_SECURITY_GROUP => true,
            self::INVALID_STATE_KMSKEY => true,
            self::INVALID_SUBNET => true,
            self::INVALID_ZIP_FILE_EXCEPTION => true,
            self::KMSKEY_ACCESS_DENIED => true,
            self::KMSKEY_NOT_FOUND => true,
            self::SUBNET_OUT_OF_IPADDRESSES => true,
        ][$value]);
    }
}
