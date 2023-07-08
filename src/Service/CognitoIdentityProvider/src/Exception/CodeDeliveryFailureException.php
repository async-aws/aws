<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when a verification code fails to deliver successfully.
 */
final class CodeDeliveryFailureException extends ClientException
{
}
