<?php

declare(strict_types=1);

namespace AsyncAws\Aws\Exception\Http;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NetworkException extends \RuntimeException implements HttpException, TransportExceptionInterface
{
}
