<?php

namespace AsyncAws\Comprehend\Enum;

final class InvalidRequestDetailReason
{
    public const DOCUMENT_SIZE_EXCEEDED = 'DOCUMENT_SIZE_EXCEEDED';
    public const PAGE_LIMIT_EXCEEDED = 'PAGE_LIMIT_EXCEEDED';
    public const TEXTRACT_ACCESS_DENIED = 'TEXTRACT_ACCESS_DENIED';
    public const UNSUPPORTED_DOC_TYPE = 'UNSUPPORTED_DOC_TYPE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DOCUMENT_SIZE_EXCEEDED => true,
            self::PAGE_LIMIT_EXCEEDED => true,
            self::TEXTRACT_ACCESS_DENIED => true,
            self::UNSUPPORTED_DOC_TYPE => true,
        ][$value]);
    }
}
