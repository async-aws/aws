<?php

namespace AsyncAws\Lambda\Enum;

final class KafkaSchemaRegistryAuthType
{
    public const BASIC_AUTH = 'BASIC_AUTH';
    public const CLIENT_CERTIFICATE_TLS_AUTH = 'CLIENT_CERTIFICATE_TLS_AUTH';
    public const SERVER_ROOT_CA_CERTIFICATE = 'SERVER_ROOT_CA_CERTIFICATE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BASIC_AUTH => true,
            self::CLIENT_CERTIFICATE_TLS_AUTH => true,
            self::SERVER_ROOT_CA_CERTIFICATE => true,
        ][$value]);
    }
}
