<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when Amazon Cognito encounters a group that already exists in the user pool.
 */
final class GroupExistsException extends ClientException
{
}
