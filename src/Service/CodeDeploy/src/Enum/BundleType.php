<?php

namespace AsyncAws\CodeDeploy\Enum;

final class BundleType
{
    public const JSON = 'JSON';
    public const TAR = 'tar';
    public const TGZ = 'tgz';
    public const YAML = 'YAML';
    public const ZIP = 'zip';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::JSON => true,
            self::TAR => true,
            self::TGZ => true,
            self::YAML => true,
            self::ZIP => true,
        ][$value]);
    }
}
