<?php

namespace AsyncAws\S3\Enum;

class RequestCharged
{
    public const REQUESTER = 'requester';
    public const AVAILABLE_REQUESTCHARGED = [
        self::REQUESTER => true,
    ];
}
