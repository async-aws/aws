<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the specified queue previously received a `PurgeQueue` request within the last 60 seconds (the time it
 * can take to delete the messages in the queue).
 */
final class PurgeQueueInProgressException extends ClientException
{
}
