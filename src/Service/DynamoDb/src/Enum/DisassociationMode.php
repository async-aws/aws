<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * Specifies how pending replication is handled before a replica is disassociated. `WAIT_FOR_REPLICATION` is the only
 * supported value.
 */
final class DisassociationMode
{
    public const WAIT_FOR_REPLICATION = 'WAIT_FOR_REPLICATION';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::WAIT_FOR_REPLICATION => true,
        ][$value]);
    }
}
