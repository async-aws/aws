<?php

namespace AsyncAws\AppSync\Enum;

/**
 * The Conflict Detection strategy to use.
 *
 * - **VERSION**: Detect conflicts based on object versions for this resolver.
 * - **NONE**: Do not detect conflicts when invoking this resolver.
 */
final class ConflictDetectionType
{
    public const NONE = 'NONE';
    public const VERSION = 'VERSION';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::VERSION => true,
        ][$value]);
    }
}
