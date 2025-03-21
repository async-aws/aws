<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class DocumentFormat
{
    public const CSV = 'csv';
    public const DOC = 'doc';
    public const DOCX = 'docx';
    public const HTML = 'html';
    public const MD = 'md';
    public const PDF = 'pdf';
    public const TXT = 'txt';
    public const XLS = 'xls';
    public const XLSX = 'xlsx';

    public static function exists(string $value): bool
    {
        return isset([
            self::CSV => true,
            self::DOC => true,
            self::DOCX => true,
            self::HTML => true,
            self::MD => true,
            self::PDF => true,
            self::TXT => true,
            self::XLS => true,
            self::XLSX => true,
        ][$value]);
    }
}
