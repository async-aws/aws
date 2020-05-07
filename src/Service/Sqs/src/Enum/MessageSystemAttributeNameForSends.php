<?php

namespace AsyncAws\Sqs\Enum;

final class MessageSystemAttributeNameForSends
{
    public const AWSTRACE_HEADER = 'AWSTraceHeader';

    public static function exists(string $value): bool
    {
        return isset([
            self::AWSTRACE_HEADER => true,
        ][$value]);
    }
}
