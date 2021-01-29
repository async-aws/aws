<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A queue with this name already exists. Amazon SQS returns this error only if the request includes attributes whose
 * values differ from those of the existing queue.
 */
final class QueueNameExistsException extends ClientException
{
}
