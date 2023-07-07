<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when Amazon Cognito isn't allowed to use your email identity. HTTP status code: 400.
 */
final class InvalidEmailRoleAccessPolicyException extends ClientException
{
}
