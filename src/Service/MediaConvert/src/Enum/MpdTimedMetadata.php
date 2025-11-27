<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To include ID3 metadata in this output: Set ID3 metadata to Passthrough. Specify this ID3 metadata in Custom ID3
 * metadata inserter. MediaConvert writes each instance of ID3 metadata in a separate Event Message (eMSG) box. To
 * exclude this ID3 metadata: Set ID3 metadata to None or leave blank.
 */
final class MpdTimedMetadata
{
    public const NONE = 'NONE';
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PASSTHROUGH => true,
        ][$value]);
    }
}
