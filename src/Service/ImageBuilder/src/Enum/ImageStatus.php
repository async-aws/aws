<?php

namespace AsyncAws\ImageBuilder\Enum;

final class ImageStatus
{
    public const AVAILABLE = 'AVAILABLE';
    public const BUILDING = 'BUILDING';
    public const CANCELLED = 'CANCELLED';
    public const CREATING = 'CREATING';
    public const DELETED = 'DELETED';
    public const DEPRECATED = 'DEPRECATED';
    public const DISABLED = 'DISABLED';
    public const DISTRIBUTING = 'DISTRIBUTING';
    public const FAILED = 'FAILED';
    public const INTEGRATING = 'INTEGRATING';
    public const PENDING = 'PENDING';
    public const TESTING = 'TESTING';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AVAILABLE => true,
            self::BUILDING => true,
            self::CANCELLED => true,
            self::CREATING => true,
            self::DELETED => true,
            self::DEPRECATED => true,
            self::DISABLED => true,
            self::DISTRIBUTING => true,
            self::FAILED => true,
            self::INTEGRATING => true,
            self::PENDING => true,
            self::TESTING => true,
        ][$value]);
    }
}
