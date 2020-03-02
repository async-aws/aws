<?php

namespace AsyncAws\S3\Enum;

class TaggingDirective
{
    public const COPY = 'COPY';
    public const REPLACE = 'REPLACE';
    public const AVAILABLE_TAGGINGDIRECTIVE = [
        self::COPY => true,
        self::REPLACE => true,
    ];
}
