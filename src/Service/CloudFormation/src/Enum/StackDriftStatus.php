<?php

namespace AsyncAws\CloudFormation\Enum;

final class StackDriftStatus
{
    public const DRIFTED = 'DRIFTED';
    public const IN_SYNC = 'IN_SYNC';
    public const NOT_CHECKED = 'NOT_CHECKED';
    public const UNKNOWN = 'UNKNOWN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DRIFTED => true,
            self::IN_SYNC => true,
            self::NOT_CHECKED => true,
            self::UNKNOWN => true,
        ][$value]);
    }
}
