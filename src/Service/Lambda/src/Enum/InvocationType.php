<?php

namespace AsyncAws\Lambda\Enum;

class InvocationType
{
    public const DRY_RUN = 'DryRun';
    public const EVENT = 'Event';
    public const REQUEST_RESPONSE = 'RequestResponse';

    public static function exists(string $value): bool
    {
        $values = [
            self::DRY_RUN => true,
            self::EVENT => true,
            self::REQUEST_RESPONSE => true,
        ];

        return isset($values[$value]);
    }
}
