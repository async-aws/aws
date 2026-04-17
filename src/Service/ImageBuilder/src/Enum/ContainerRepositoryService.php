<?php

namespace AsyncAws\ImageBuilder\Enum;

final class ContainerRepositoryService
{
    public const ECR = 'ECR';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ECR => true,
        ][$value]);
    }
}
