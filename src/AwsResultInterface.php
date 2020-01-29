<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

interface AwsResultInterface
{
    public function populate(array $data);
}