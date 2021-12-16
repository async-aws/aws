<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An API function was called too frequently.
 */
final class ThrottlingException extends ClientException
{
}
