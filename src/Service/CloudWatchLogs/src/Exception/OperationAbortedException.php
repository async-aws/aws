<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Multiple concurrent requests to update the same resource were in conflict.
 */
final class OperationAbortedException extends ClientException
{
}
