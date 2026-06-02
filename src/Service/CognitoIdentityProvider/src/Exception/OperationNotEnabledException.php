<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when an operation is not available in the current region or for the current user pool
 * configuration. This can occur when attempting to perform operations that are not supported in secondary replica
 * regions.
 */
final class OperationNotEnabledException extends ClientException
{
}
