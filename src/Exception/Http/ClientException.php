<?php

declare(strict_types=1);

namespace WorkingTitle\Aws\Exception;

use Symfony\Component\HttpClient\Exception\HttpExceptionTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

/**
 * Represents a 4xx response.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class ClientException extends \RuntimeException implements HttpException, ClientExceptionInterface
{
    use HttpExceptionTrait;
}
