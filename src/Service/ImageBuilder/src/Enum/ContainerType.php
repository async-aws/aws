<?php

namespace AsyncAws\ImageBuilder\Enum;

final class ContainerType
{
    public const DOCKER = 'DOCKER';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DOCKER => true,
        ][$value]);
    }
}
