<?php

namespace AsyncAws\CodeBuild\Enum;

final class CacheMode
{
    public const LOCAL_CUSTOM_CACHE = 'LOCAL_CUSTOM_CACHE';
    public const LOCAL_DOCKER_LAYER_CACHE = 'LOCAL_DOCKER_LAYER_CACHE';
    public const LOCAL_SOURCE_CACHE = 'LOCAL_SOURCE_CACHE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LOCAL_CUSTOM_CACHE => true,
            self::LOCAL_DOCKER_LAYER_CACHE => true,
            self::LOCAL_SOURCE_CACHE => true,
        ][$value]);
    }
}
