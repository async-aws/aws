<?php

declare(strict_types=1);

namespace WorkingTitle\Aws\S3;

use Symfony\Contracts\HttpClient\ResponseInterface;

class GetObjectResult
{
    public function __construct(ResponseInterface $response)
    {
    }
}
