<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified resource does not exist.
 */
final class ResourceNotFoundException extends ClientException
{
}
