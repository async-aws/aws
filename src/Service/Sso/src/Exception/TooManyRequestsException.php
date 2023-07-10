<?php

namespace AsyncAws\Sso\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the request is being made too frequently and is more than what the server can handle.
 */
final class TooManyRequestsException extends ClientException
{
}
