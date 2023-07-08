<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The parameter already exists. You can't create duplicate parameters.
 */
final class ParameterAlreadyExistsException extends ClientException
{
}
