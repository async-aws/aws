<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The parameter couldn't be found. Verify the name and try again.
 */
final class ParameterNotFoundException extends ClientException
{
}
