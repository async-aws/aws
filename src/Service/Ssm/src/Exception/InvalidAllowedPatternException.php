<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request doesn't meet the regular expression requirement.
 */
final class InvalidAllowedPatternException extends ClientException
{
}
