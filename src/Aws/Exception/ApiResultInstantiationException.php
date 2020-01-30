<?php

declare(strict_types=1);

namespace AsyncAws\Aws\Exception;

use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * When we cannot instantiate the result class for some reason.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ApiResultInstantiationException extends \RuntimeException implements Exception
{
    private $resultClass;

    public static function create(ResponseInterface $response, string $class, \Throwable $previous): self
    {
        $e = new self($response, $response->getStatusCode(), $previous);
        $e->resultClass = $class;

        return $e;
    }

    public function getResultClass(): string
    {
        return $this->resultClass;
    }
}
