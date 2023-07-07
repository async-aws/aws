<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified parameter version wasn't found. Verify the parameter name and version, and try again.
 */
final class ParameterVersionNotFoundException extends ClientException
{
}
