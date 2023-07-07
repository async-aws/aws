<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Can't perform multiple operations on a tag simultaneously. Perform the operations sequentially.
 */
final class ConcurrentAccessException extends ClientException
{
}
