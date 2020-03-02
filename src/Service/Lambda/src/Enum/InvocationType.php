<?php

namespace AsyncAws\Lambda\Enum;

class InvocationType
{
    public const DRY_RUN = 'DryRun';
    public const EVENT = 'Event';
    public const REQUEST_RESPONSE = 'RequestResponse';
    public const AVAILABLE_INVOCATIONTYPE = [
        self::DRY_RUN => true,
        self::EVENT => true,
        self::REQUEST_RESPONSE => true,
    ];
}
