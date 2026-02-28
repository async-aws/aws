<?php

namespace AsyncAws\S3\Enum;

final class TransitionDefaultMinimumObjectSize
{
    public const ALL_STORAGE_CLASSES_128K = 'all_storage_classes_128K';
    public const VARIES_BY_STORAGE_CLASS = 'varies_by_storage_class';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_STORAGE_CLASSES_128K => true,
            self::VARIES_BY_STORAGE_CLASS => true,
        ][$value]);
    }
}
