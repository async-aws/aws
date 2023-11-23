<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * When the request to a queue is not HTTPS and SigV4.
 */
final class InvalidSecurityException extends ClientException
{
}
