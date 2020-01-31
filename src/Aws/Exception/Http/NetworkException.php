<?php

declare(strict_types=1);

namespace AsyncAws\Aws\Exception\Http;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Request could not be sent due network error.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class NetworkException extends \RuntimeException implements HttpException, TransportExceptionInterface
{
}
