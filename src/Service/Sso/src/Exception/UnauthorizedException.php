<?php

namespace AsyncAws\Sso\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the request is not authorized. This can happen due to an invalid access token in the request.
 */
final class UnauthorizedException extends ClientException
{
}
