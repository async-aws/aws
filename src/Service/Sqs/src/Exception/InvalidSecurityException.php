<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was not made over HTTPS or did not use SigV4 for signing.
 */
final class InvalidSecurityException extends ClientException
{
}
