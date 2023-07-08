<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when the user has made too many requests for a given operation.
 */
final class TooManyRequestsException extends ClientException
{
}
