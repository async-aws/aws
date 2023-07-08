<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when the Amazon Cognito service encounters a user validation exception with the Lambda
 * service.
 */
final class UserLambdaValidationException extends ClientException
{
}
