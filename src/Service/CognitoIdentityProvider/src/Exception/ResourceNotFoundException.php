<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when the Amazon Cognito service can't find the requested resource.
 */
final class ResourceNotFoundException extends ClientException
{
}
