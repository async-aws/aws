<?php

namespace AsyncAws\Sso\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that a problem occurred with the input to the request. For example, a required parameter might be missing
 * or out of range.
 */
final class InvalidRequestException extends ClientException
{
}
