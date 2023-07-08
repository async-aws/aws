<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * No health check exists with the specified ID.
 */
final class NoSuchHealthCheckException extends ClientException
{
}
