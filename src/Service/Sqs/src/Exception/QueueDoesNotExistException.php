<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified queue doesn't exist.
 */
final class QueueDoesNotExistException extends ClientException
{
}
