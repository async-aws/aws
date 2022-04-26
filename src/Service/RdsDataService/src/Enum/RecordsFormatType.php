<?php

namespace AsyncAws\RdsDataService\Enum;

/**
 * A value that indicates whether to format the result set as a single JSON string. This parameter only applies to
 * `SELECT` statements and is ignored for other types of statements. Allowed values are `NONE` and `JSON`. The default
 * value is `NONE`. The result is returned in the `formattedRecords` field.
 * For usage information about the JSON format for result sets, see Using the Data API in the *Amazon Aurora User
 * Guide*.
 *
 * @see https://docs.aws.amazon.com/AmazonRDS/latest/AuroraUserGuide/data-api.html
 */
final class RecordsFormatType
{
    public const JSON = 'JSON';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::JSON => true,
            self::NONE => true,
        ][$value]);
    }
}
