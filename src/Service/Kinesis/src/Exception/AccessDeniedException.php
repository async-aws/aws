<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Specifies that you do not have the permissions required to perform this operation.
 */
final class AccessDeniedException extends ClientException
{
}
