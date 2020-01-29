<?php

declare(strict_types=1);

namespace WorkingTitle\Sqs\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

class SendMessageResult
{
    public function __construct(ResponseInterface $response)
    {
        // Do something..
    }
}
