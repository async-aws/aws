<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The filter value isn't valid. Verify the value and try again.
 */
final class InvalidFilterValueException extends ClientException
{
}
