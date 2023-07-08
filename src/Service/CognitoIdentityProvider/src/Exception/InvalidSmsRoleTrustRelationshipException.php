<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when the trust relationship is not valid for the role provided for SMS configuration. This
 * can happen if you don't trust `cognito-idp.amazonaws.com` or the external ID provided in the role does not match what
 * is provided in the SMS configuration for the user pool.
 */
final class InvalidSmsRoleTrustRelationshipException extends ClientException
{
}
