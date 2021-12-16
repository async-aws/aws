<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The minimum number of required application names was not specified.
 */
final class ApplicationNameRequiredException extends ClientException
{
}
