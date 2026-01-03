<?php

namespace AsyncAws\Lambda\Enum;

final class StateReasonCode
{
    public const CAPACITY_PROVIDER_SCALING_LIMIT_EXCEEDED = 'CapacityProviderScalingLimitExceeded';
    public const CREATING = 'Creating';
    public const DISABLED_KMSKEY = 'DisabledKMSKey';
    public const DISALLOWED_BY_VPC_ENCRYPTION_CONTROL = 'DisallowedByVpcEncryptionControl';
    public const DRAINING_DURABLE_EXECUTIONS = 'DrainingDurableExecutions';
    public const EC2REQUEST_LIMIT_EXCEEDED = 'EC2RequestLimitExceeded';
    public const EFSIOERROR = 'EFSIOError';
    public const EFSMOUNT_CONNECTIVITY_ERROR = 'EFSMountConnectivityError';
    public const EFSMOUNT_FAILURE = 'EFSMountFailure';
    public const EFSMOUNT_TIMEOUT = 'EFSMountTimeout';
    public const ENI_LIMIT_EXCEEDED = 'EniLimitExceeded';
    public const FUNCTION_ERROR = 'FunctionError';
    public const FUNCTION_ERROR_EXTENSION_INIT_ERROR = 'FunctionError.ExtensionInitError';
    public const FUNCTION_ERROR_INIT_RESOURCE_EXHAUSTED = 'FunctionError.InitResourceExhausted';
    public const FUNCTION_ERROR_INIT_TIMEOUT = 'FunctionError.InitTimeout';
    public const FUNCTION_ERROR_INVALID_ENTRY_POINT = 'FunctionError.InvalidEntryPoint';
    public const FUNCTION_ERROR_INVALID_WORKING_DIRECTORY = 'FunctionError.InvalidWorkingDirectory';
    public const FUNCTION_ERROR_PERMISSION_DENIED = 'FunctionError.PermissionDenied';
    public const FUNCTION_ERROR_RUNTIME_INIT_ERROR = 'FunctionError.RuntimeInitError';
    public const FUNCTION_ERROR_TOO_MANY_EXTENSIONS = 'FunctionError.TooManyExtensions';
    public const IDLE = 'Idle';
    public const IMAGE_ACCESS_DENIED = 'ImageAccessDenied';
    public const IMAGE_DELETED = 'ImageDeleted';
    public const INSUFFICIENT_CAPACITY = 'InsufficientCapacity';
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
    public const RESTORING = 'Restoring';
    public const SUBNET_OUT_OF_IPADDRESSES = 'SubnetOutOfIPAddresses';
    public const VCPU_LIMIT_EXCEEDED = 'VcpuLimitExceeded';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CAPACITY_PROVIDER_SCALING_LIMIT_EXCEEDED => true,
            self::CREATING => true,
            self::DISABLED_KMSKEY => true,
            self::DISALLOWED_BY_VPC_ENCRYPTION_CONTROL => true,
            self::DRAINING_DURABLE_EXECUTIONS => true,
            self::EC2REQUEST_LIMIT_EXCEEDED => true,
            self::EFSIOERROR => true,
            self::EFSMOUNT_CONNECTIVITY_ERROR => true,
            self::EFSMOUNT_FAILURE => true,
            self::EFSMOUNT_TIMEOUT => true,
            self::ENI_LIMIT_EXCEEDED => true,
            self::FUNCTION_ERROR => true,
            self::FUNCTION_ERROR_EXTENSION_INIT_ERROR => true,
            self::FUNCTION_ERROR_INIT_RESOURCE_EXHAUSTED => true,
            self::FUNCTION_ERROR_INIT_TIMEOUT => true,
            self::FUNCTION_ERROR_INVALID_ENTRY_POINT => true,
            self::FUNCTION_ERROR_INVALID_WORKING_DIRECTORY => true,
            self::FUNCTION_ERROR_PERMISSION_DENIED => true,
            self::FUNCTION_ERROR_RUNTIME_INIT_ERROR => true,
            self::FUNCTION_ERROR_TOO_MANY_EXTENSIONS => true,
            self::IDLE => true,
            self::IMAGE_ACCESS_DENIED => true,
            self::IMAGE_DELETED => true,
            self::INSUFFICIENT_CAPACITY => true,
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
            self::RESTORING => true,
            self::SUBNET_OUT_OF_IPADDRESSES => true,
            self::VCPU_LIMIT_EXCEEDED => true,
        ][$value]);
    }
}
