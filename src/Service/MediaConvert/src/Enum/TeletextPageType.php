<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * A page type as defined in the standard ETSI EN 300 468, Table 94.
 */
final class TeletextPageType
{
    public const PAGE_TYPE_ADDL_INFO = 'PAGE_TYPE_ADDL_INFO';
    public const PAGE_TYPE_HEARING_IMPAIRED_SUBTITLE = 'PAGE_TYPE_HEARING_IMPAIRED_SUBTITLE';
    public const PAGE_TYPE_INITIAL = 'PAGE_TYPE_INITIAL';
    public const PAGE_TYPE_PROGRAM_SCHEDULE = 'PAGE_TYPE_PROGRAM_SCHEDULE';
    public const PAGE_TYPE_SUBTITLE = 'PAGE_TYPE_SUBTITLE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PAGE_TYPE_ADDL_INFO => true,
            self::PAGE_TYPE_HEARING_IMPAIRED_SUBTITLE => true,
            self::PAGE_TYPE_INITIAL => true,
            self::PAGE_TYPE_PROGRAM_SCHEDULE => true,
            self::PAGE_TYPE_SUBTITLE => true,
        ][$value]);
    }
}
