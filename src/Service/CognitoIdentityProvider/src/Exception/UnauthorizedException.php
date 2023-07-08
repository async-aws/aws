<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Exception that is thrown when the request isn't authorized. This can happen due to an invalid access token in the
 * request.
 */
final class UnauthorizedException extends ClientException
{
}
