<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified filter option isn't valid. Valid options are Equals and BeginsWith. For Path filter, valid options are
 * Recursive and OneLevel.
 */
final class InvalidFilterOptionException extends ClientException
{
}
