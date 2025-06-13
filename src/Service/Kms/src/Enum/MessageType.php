<?php

namespace AsyncAws\Kms\Enum;

final class MessageType
{
    public const DIGEST = 'DIGEST';
    public const EXTERNAL_MU = 'EXTERNAL_MU';
    public const RAW = 'RAW';

    public static function exists(string $value): bool
    {
        return isset([
            self::DIGEST => true,
            self::EXTERNAL_MU => true,
            self::RAW => true,
        ][$value]);
    }
}
