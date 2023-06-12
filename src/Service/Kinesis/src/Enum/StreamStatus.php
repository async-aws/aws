<?php

namespace AsyncAws\Kinesis\Enum;

final class StreamStatus
{
    public const ACTIVE = 'ACTIVE';
    public const CREATING = 'CREATING';
    public const DELETING = 'DELETING';
    public const UPDATING = 'UPDATING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::CREATING => true,
            self::DELETING => true,
            self::UPDATING => true,
        ][$value]);
    }
}
