<?php

namespace AsyncAws\CodeBuild\Enum;

final class MachineType
{
    public const GENERAL = 'GENERAL';
    public const NVME = 'NVME';

    public static function exists(string $value): bool
    {
        return isset([
            self::GENERAL => true,
            self::NVME => true,
        ][$value]);
    }
}
