<?php

namespace AsyncAws\Ec2\Enum;

final class ImageState
{
    public const AVAILABLE = 'available';
    public const DEREGISTERED = 'deregistered';
    public const DISABLED = 'disabled';
    public const ERROR = 'error';
    public const FAILED = 'failed';
    public const INVALID = 'invalid';
    public const PENDING = 'pending';
    public const TRANSIENT = 'transient';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AVAILABLE => true,
            self::DEREGISTERED => true,
            self::DISABLED => true,
            self::ERROR => true,
            self::FAILED => true,
            self::INVALID => true,
            self::PENDING => true,
            self::TRANSIENT => true,
        ][$value]);
    }
}
