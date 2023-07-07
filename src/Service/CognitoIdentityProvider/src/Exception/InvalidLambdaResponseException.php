<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when Amazon Cognito encounters an invalid Lambda response.
 */
final class InvalidLambdaResponseException extends ClientException
{
}
