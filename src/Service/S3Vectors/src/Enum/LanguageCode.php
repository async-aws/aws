<?php

namespace AsyncAws\S3Vectors\Enum;

/**
 * The analysis language for a text search analyzer. Valid values are `en` (English), `fr` (French), `es` (Spanish),
 * `pt` (Portuguese), `it` (Italian), `de` (German), `nl` (Dutch), `zh` (Chinese), `ja` (Japanese), and `ko` (Korean).
 */
final class LanguageCode
{
    public const DE = 'de';
    public const EN = 'en';
    public const ES = 'es';
    public const FR = 'fr';
    public const IT = 'it';
    public const JA = 'ja';
    public const KO = 'ko';
    public const NL = 'nl';
    public const PT = 'pt';
    public const ZH = 'zh';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DE => true,
            self::EN => true,
            self::ES => true,
            self::FR => true,
            self::IT => true,
            self::JA => true,
            self::KO => true,
            self::NL => true,
            self::PT => true,
            self::ZH => true,
        ][$value]);
    }
}
