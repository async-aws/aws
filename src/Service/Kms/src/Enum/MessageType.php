<?php

namespace AsyncAws\Kms\Enum;

/**
 * Tells KMS whether the value of the `Message` parameter is a message or message digest. The default value, RAW,
 * indicates a message. To indicate a message digest, enter `DIGEST`.
 */
final class MessageType
{
    public const DIGEST = 'DIGEST';
    public const RAW = 'RAW';

    public static function exists(string $value): bool
    {
        return isset([
            self::DIGEST => true,
            self::RAW => true,
        ][$value]);
    }
}
