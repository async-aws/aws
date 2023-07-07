<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when the Amazon Cognito service encounters an invalid parameter.
 */
final class InvalidParameterException extends ClientException
{
}
