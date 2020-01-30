<?php

declare(strict_types=1);

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

class GetObjectResult
{
    public function __construct(ResponseInterface $response)
    {
    }
}
