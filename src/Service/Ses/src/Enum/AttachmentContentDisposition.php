<?php

namespace AsyncAws\Ses\Enum;

final class AttachmentContentDisposition
{
    public const ATTACHMENT = 'ATTACHMENT';
    public const INLINE = 'INLINE';

    public static function exists(string $value): bool
    {
        return isset([
            self::ATTACHMENT => true,
            self::INLINE => true,
        ][$value]);
    }
}
