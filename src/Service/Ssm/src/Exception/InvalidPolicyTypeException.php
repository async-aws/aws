<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The policy type isn't supported. Parameter Store supports the following policy types: Expiration,
 * ExpirationNotification, and NoChangeNotification.
 */
final class InvalidPolicyTypeException extends ClientException
{
}
