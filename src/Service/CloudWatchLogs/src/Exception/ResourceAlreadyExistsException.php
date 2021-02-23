<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified resource already exists.
 */
final class ResourceAlreadyExistsException extends ClientException
{
}
