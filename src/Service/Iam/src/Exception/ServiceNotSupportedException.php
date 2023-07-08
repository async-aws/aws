<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified service does not support service-specific credentials.
 */
final class ServiceNotSupportedException extends ClientException
{
}
