<?php

namespace AsyncAws\AppSync\Enum;

/**
 * The resolver type.
 *
 * - **UNIT**: A UNIT resolver type. A UNIT resolver is the default resolver type. A UNIT resolver enables you to
 *   execute a GraphQL query against a single data source.
 * - **PIPELINE**: A PIPELINE resolver type. A PIPELINE resolver enables you to execute a series of `Function` in a
 *   serial manner. You can use a pipeline resolver to execute a GraphQL query against multiple data sources.
 */
final class ResolverKind
{
    public const PIPELINE = 'PIPELINE';
    public const UNIT = 'UNIT';

    public static function exists(string $value): bool
    {
        return isset([
            self::PIPELINE => true,
            self::UNIT => true,
        ][$value]);
    }
}
