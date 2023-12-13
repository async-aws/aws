<?php

namespace AsyncAws\LocationService\Enum;

final class OptimizationMode
{
    public const FASTEST_ROUTE = 'FastestRoute';
    public const SHORTEST_ROUTE = 'ShortestRoute';

    public static function exists(string $value): bool
    {
        return isset([
            self::FASTEST_ROUTE => true,
            self::SHORTEST_ROUTE => true,
        ][$value]);
    }
}
