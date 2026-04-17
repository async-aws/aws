<?php

namespace AsyncAws\Lambda\Enum;

final class SourceAccessType
{
    public const BASIC_AUTH = 'BASIC_AUTH';
    public const CLIENT_CERTIFICATE_TLS_AUTH = 'CLIENT_CERTIFICATE_TLS_AUTH';
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
            self::SASL_SCRAM_256_AUTH => true,
            self::SASL_SCRAM_512_AUTH => true,
            self::SERVER_ROOT_CA_CERTIFICATE => true,
            self::VIRTUAL_HOST => true,
            self::VPC_SECURITY_GROUP => true,
            self::VPC_SUBNET => true,
        ][$value]);
    }
}
