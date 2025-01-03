<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Ensure that the `QueueUrl` is correct and that the queue has not been deleted.
 */
final class QueueDoesNotExistException extends ClientException
{
}
