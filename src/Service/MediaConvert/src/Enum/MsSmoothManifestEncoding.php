<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Manifest encoding to specify the encoding format for the server and client manifest. Valid options are utf8 and
 * utf16.
 */
final class MsSmoothManifestEncoding
{
    public const UTF16 = 'UTF16';
    public const UTF8 = 'UTF8';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::UTF16 => true,
            self::UTF8 => true,
        ][$value]);
    }
}
