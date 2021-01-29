<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The message contains characters outside the allowed set.
 */
final class InvalidMessageContentsException extends ClientException
{
}
