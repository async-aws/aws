<?php

namespace AsyncAws\Rekognition\Enum;

final class ContentClassifier
{
    public const FREE_OF_ADULT_CONTENT = 'FreeOfAdultContent';
    public const FREE_OF_PERSONALLY_IDENTIFIABLE_INFORMATION = 'FreeOfPersonallyIdentifiableInformation';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FREE_OF_ADULT_CONTENT => true,
            self::FREE_OF_PERSONALLY_IDENTIFIABLE_INFORMATION => true,
        ][$value]);
    }
}
