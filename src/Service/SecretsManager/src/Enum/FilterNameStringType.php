<?php

namespace AsyncAws\SecretsManager\Enum;

final class FilterNameStringType
{
    public const ALL = 'all';
    public const DESCRIPTION = 'description';
    public const NAME = 'name';
    public const OWNING_SERVICE = 'owning-service';
    public const PRIMARY_REGION = 'primary-region';
    public const TAG_KEY = 'tag-key';
    public const TAG_VALUE = 'tag-value';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::DESCRIPTION => true,
            self::NAME => true,
            self::OWNING_SERVICE => true,
            self::PRIMARY_REGION => true,
            self::TAG_KEY => true,
            self::TAG_VALUE => true,
        ][$value]);
    }
}
