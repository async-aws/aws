<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The length of all the batch messages put together is more than the limit.
 */
final class BatchRequestTooLongException extends ClientException
{
}
