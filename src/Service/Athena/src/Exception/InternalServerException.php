<?php

namespace AsyncAws\Athena\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates a platform issue, which may be due to a transient condition or outage.
 */
final class InternalServerException extends ClientException
{
}
