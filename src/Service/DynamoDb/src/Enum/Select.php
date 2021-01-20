<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * The attributes to be returned in the result. You can retrieve all item attributes, specific item attributes, the
 * count of matching items, or in the case of an index, some or all of the attributes projected into the index.
 *
 * - `ALL_ATTRIBUTES` - Returns all of the item attributes from the specified table or index. If you query a local
 *   secondary index, then for each matching item in the index, DynamoDB fetches the entire item from the parent table.
 *   If the index is configured to project all item attributes, then all of the data can be obtained from the local
 *   secondary index, and no fetching is required.
 * - `ALL_PROJECTED_ATTRIBUTES` - Allowed only when querying an index. Retrieves all attributes that have been projected
 *   into the index. If the index is configured to project all attributes, this return value is equivalent to specifying
 *   `ALL_ATTRIBUTES`.
 * - `COUNT` - Returns the number of matching items, rather than the matching items themselves.
 * - `SPECIFIC_ATTRIBUTES` - Returns only the attributes listed in `AttributesToGet`. This return value is equivalent to
 *   specifying `AttributesToGet` without specifying any value for `Select`.
 *   If you query or scan a local secondary index and request only attributes that are projected into that index, the
 *   operation will read only the index and not the table. If any of the requested attributes are not projected into the
 *   local secondary index, DynamoDB fetches each of these attributes from the parent table. This extra fetching incurs
 *   additional throughput cost and latency.
 *   If you query or scan a global secondary index, you can only request attributes that are projected into the index.
 *   Global secondary index queries cannot fetch attributes from the parent table.
 *
 * If neither `Select` nor `AttributesToGet` are specified, DynamoDB defaults to `ALL_ATTRIBUTES` when accessing a
 * table, and `ALL_PROJECTED_ATTRIBUTES` when accessing an index. You cannot use both `Select` and `AttributesToGet`
 * together in a single request, unless the value for `Select` is `SPECIFIC_ATTRIBUTES`. (This usage is equivalent to
 * specifying `AttributesToGet` without any value for `Select`.)
 *
 * > If you use the `ProjectionExpression` parameter, then the value for `Select` can only be `SPECIFIC_ATTRIBUTES`. Any
 * > other value for `Select` will return an error.
 */
final class Select
{
    public const ALL_ATTRIBUTES = 'ALL_ATTRIBUTES';
    public const ALL_PROJECTED_ATTRIBUTES = 'ALL_PROJECTED_ATTRIBUTES';
    public const COUNT = 'COUNT';
    public const SPECIFIC_ATTRIBUTES = 'SPECIFIC_ATTRIBUTES';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_ATTRIBUTES => true,
            self::ALL_PROJECTED_ATTRIBUTES => true,
            self::COUNT => true,
            self::SPECIFIC_ATTRIBUTES => true,
        ][$value]);
    }
}
