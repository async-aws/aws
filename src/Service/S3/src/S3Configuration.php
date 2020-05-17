<?php

declare(strict_types=1);

namespace AsyncAws\S3;

use AsyncAws\Core\Configuration;

class S3Configuration extends Configuration
{
    public const OPTION_PATH_STYLE_ENDPOINT = 'pathStyleEndpoint';

    protected const AVAILABLE_OPTIONS = [
        self::OPTION_PATH_STYLE_ENDPOINT => true,
    ] + parent::AVAILABLE_OPTIONS;

    protected const DEFAULT_OPTIONS = [
        self::OPTION_PATH_STYLE_ENDPOINT => 'false',
    ] + parent::DEFAULT_OPTIONS;
}
