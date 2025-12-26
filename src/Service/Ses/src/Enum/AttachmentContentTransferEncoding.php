<?php

namespace AsyncAws\Ses\Enum;

final class AttachmentContentTransferEncoding
{
    public const BASE64 = 'BASE64';
    public const QUOTED_PRINTABLE = 'QUOTED_PRINTABLE';
    public const SEVEN_BIT = 'SEVEN_BIT';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BASE64 => true,
            self::QUOTED_PRINTABLE => true,
            self::SEVEN_BIT => true,
        ][$value]);
    }
}
