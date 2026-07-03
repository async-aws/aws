<?php

namespace AsyncAws\Ses\Enum;

final class VerificationError
{
    public const DNS_SERVER_ERROR = 'DNS_SERVER_ERROR';
    public const HOST_NOT_FOUND = 'HOST_NOT_FOUND';
    public const INVALID_VALUE = 'INVALID_VALUE';
    public const REPLICATION_ACCESS_DENIED = 'REPLICATION_ACCESS_DENIED';
    public const REPLICATION_PRIMARY_BYO_DKIM_NOT_SUPPORTED = 'REPLICATION_PRIMARY_BYO_DKIM_NOT_SUPPORTED';
    public const REPLICATION_PRIMARY_INVALID_REGION = 'REPLICATION_PRIMARY_INVALID_REGION';
    public const REPLICATION_PRIMARY_NOT_FOUND = 'REPLICATION_PRIMARY_NOT_FOUND';
    public const REPLICATION_REPLICA_AS_PRIMARY_NOT_SUPPORTED = 'REPLICATION_REPLICA_AS_PRIMARY_NOT_SUPPORTED';
    public const SERVICE_ERROR = 'SERVICE_ERROR';
    public const TYPE_NOT_FOUND = 'TYPE_NOT_FOUND';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DNS_SERVER_ERROR => true,
            self::HOST_NOT_FOUND => true,
            self::INVALID_VALUE => true,
            self::REPLICATION_ACCESS_DENIED => true,
            self::REPLICATION_PRIMARY_BYO_DKIM_NOT_SUPPORTED => true,
            self::REPLICATION_PRIMARY_INVALID_REGION => true,
            self::REPLICATION_PRIMARY_NOT_FOUND => true,
            self::REPLICATION_REPLICA_AS_PRIMARY_NOT_SUPPORTED => true,
            self::SERVICE_ERROR => true,
            self::TYPE_NOT_FOUND => true,
        ][$value]);
    }
}
