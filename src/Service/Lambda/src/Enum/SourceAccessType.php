<?php

namespace AsyncAws\Lambda\Enum;

final class SourceAccessType
{
    public const BASIC_AUTH = 'BASIC_AUTH';
    public const CLIENT_CERTIFICATE_TLS_AUTH = 'CLIENT_CERTIFICATE_TLS_AUTH';
    public const IAM_AUTH = 'IAM_AUTH';
    public const IAM_OAUTHBEARER_AUTH = 'IAM_OAUTHBEARER_AUTH';
    public const OAUTHBEARER_AUDIENCE = 'OAUTHBEARER_AUDIENCE';
    public const OAUTHBEARER_AUTH = 'OAUTHBEARER_AUTH';
    public const OAUTHBEARER_IDENTITY_POOL = 'OAUTHBEARER_IDENTITY_POOL';
    public const OAUTHBEARER_LOGICAL_CLUSTER = 'OAUTHBEARER_LOGICAL_CLUSTER';
    public const OAUTHBEARER_SCOPE = 'OAUTHBEARER_SCOPE';
    public const SASL_SCRAM_256_AUTH = 'SASL_SCRAM_256_AUTH';
    public const SASL_SCRAM_512_AUTH = 'SASL_SCRAM_512_AUTH';
    public const SERVER_ROOT_CA_CERTIFICATE = 'SERVER_ROOT_CA_CERTIFICATE';
    public const VIRTUAL_HOST = 'VIRTUAL_HOST';
    public const VPC_SECURITY_GROUP = 'VPC_SECURITY_GROUP';
    public const VPC_SUBNET = 'VPC_SUBNET';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BASIC_AUTH => true,
            self::CLIENT_CERTIFICATE_TLS_AUTH => true,
            self::IAM_AUTH => true,
            self::IAM_OAUTHBEARER_AUTH => true,
            self::OAUTHBEARER_AUDIENCE => true,
            self::OAUTHBEARER_AUTH => true,
            self::OAUTHBEARER_IDENTITY_POOL => true,
            self::OAUTHBEARER_LOGICAL_CLUSTER => true,
            self::OAUTHBEARER_SCOPE => true,
            self::SASL_SCRAM_256_AUTH => true,
            self::SASL_SCRAM_512_AUTH => true,
            self::SERVER_ROOT_CA_CERTIFICATE => true,
            self::VIRTUAL_HOST => true,
            self::VPC_SECURITY_GROUP => true,
            self::VPC_SUBNET => true,
        ][$value]);
    }
}
