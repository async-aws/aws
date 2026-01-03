<?php

namespace AsyncAws\Rekognition\Enum;

final class ProjectStatus
{
    public const CREATED = 'CREATED';
    public const CREATING = 'CREATING';
    public const DELETING = 'DELETING';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CREATED => true,
            self::CREATING => true,
            self::DELETING => true,
        ][$value]);
    }
}
