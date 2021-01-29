<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You must wait 60 seconds after deleting a queue before you can create another queue with the same name.
 */
final class QueueDeletedRecentlyException extends ClientException
{
}
