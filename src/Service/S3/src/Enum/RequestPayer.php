<?php

namespace AsyncAws\S3\Enum;

class RequestPayer
{
    public const REQUESTER = 'requester';
    public const AVAILABLE_REQUESTPAYER = [
        self::REQUESTER => true,
    ];
}
