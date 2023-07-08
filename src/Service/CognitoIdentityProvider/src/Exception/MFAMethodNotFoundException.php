<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when Amazon Cognito can't find a multi-factor authentication (MFA) method.
 */
final class MFAMethodNotFoundException extends ClientException
{
}
