<?php

namespace AsyncAws\CodeBuild\Enum;

final class MachineType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const GENERAL = 'GENERAL';
    public const NVME = 'NVME';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::GENERAL => true,
            self::NVME => true,
        ][$value]);
    }
}
