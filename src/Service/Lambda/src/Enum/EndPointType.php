<?php

namespace AsyncAws\Lambda\Enum;

final class EndPointType
{
    public const KAFKA_BOOTSTRAP_SERVERS = 'KAFKA_BOOTSTRAP_SERVERS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KAFKA_BOOTSTRAP_SERVERS => true,
        ][$value]);
    }
}
