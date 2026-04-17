<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have specified a client token for an operation using parameter values that differ from a previous request that
 * used the same client token.
 */
final class IdempotentParameterMismatchException extends ClientException
{
}
