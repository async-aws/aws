<?php

namespace AsyncAws\Lambda\Enum;

final class FunctionResponseType
{
    public const REPORT_BATCH_ITEM_FAILURES = 'ReportBatchItemFailures';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::REPORT_BATCH_ITEM_FAILURES => true,
        ][$value]);
    }
}
