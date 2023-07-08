<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when Amazon Cognito encounters an unexpected exception with Lambda.
 */
final class UnexpectedLambdaException extends ClientException
{
}
