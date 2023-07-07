<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A reusable delegation set with the specified ID does not exist.
 */
final class NoSuchDelegationSetException extends ClientException
{
}
