<?php

namespace AsyncAws\BedrockAgent\Enum;

final class DocumentStatus
{
    public const DELETE_IN_PROGRESS = 'DELETE_IN_PROGRESS';
    public const DELETING = 'DELETING';
    public const FAILED = 'FAILED';
    public const IGNORED = 'IGNORED';
    public const INDEXED = 'INDEXED';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const METADATA_PARTIALLY_INDEXED = 'METADATA_PARTIALLY_INDEXED';
    public const METADATA_UPDATE_FAILED = 'METADATA_UPDATE_FAILED';
    public const NOT_FOUND = 'NOT_FOUND';
    public const PARTIALLY_INDEXED = 'PARTIALLY_INDEXED';
    public const PENDING = 'PENDING';
    public const STARTING = 'STARTING';

    public static function exists(string $value): bool
    {
        return isset([
            self::DELETE_IN_PROGRESS => true,
            self::DELETING => true,
            self::FAILED => true,
            self::IGNORED => true,
            self::INDEXED => true,
            self::IN_PROGRESS => true,
            self::METADATA_PARTIALLY_INDEXED => true,
            self::METADATA_UPDATE_FAILED => true,
            self::NOT_FOUND => true,
            self::PARTIALLY_INDEXED => true,
            self::PENDING => true,
            self::STARTING => true,
        ][$value]);
    }
}
