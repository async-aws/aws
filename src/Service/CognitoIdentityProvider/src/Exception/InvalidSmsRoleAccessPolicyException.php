<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is returned when the role provided for SMS configuration doesn't have permission to publish using
 * Amazon SNS.
 */
final class InvalidSmsRoleAccessPolicyException extends ClientException
{
}
