<?php

namespace AsyncAws\S3\Enum;

class MetadataDirective
{
    public const COPY = 'COPY';
    public const REPLACE = 'REPLACE';
    public const AVAILABLE_METADATADIRECTIVE = [
        self::COPY => true,
        self::REPLACE => true,
    ];
}
