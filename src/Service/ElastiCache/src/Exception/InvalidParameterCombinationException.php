<?php

namespace AsyncAws\ElastiCache\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Two or more incompatible parameters were specified.
 */
final class InvalidParameterCombinationException extends ClientException
{
}
