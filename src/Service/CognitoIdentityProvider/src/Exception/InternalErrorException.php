<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when Amazon Cognito encounters an internal error.
 */
final class InternalErrorException extends ClientException
{
}
