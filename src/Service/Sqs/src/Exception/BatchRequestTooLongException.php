<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The length of all the messages put together is more than the limit.
 */
final class BatchRequestTooLongException extends ClientException
{
}
