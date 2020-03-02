<?php

namespace AsyncAws\S3\Enum;

class RequestCharged
{
    public const REQUESTER = 'requester';

    public static function exists(string $value): bool
    {
        $values = [
            self::REQUESTER => true,
        ];

        return isset($values[$value]);
    }
}
