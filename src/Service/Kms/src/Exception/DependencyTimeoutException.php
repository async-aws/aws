<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The system timed out while trying to fulfill the request. You can retry the request.
 */
final class DependencyTimeoutException extends ClientException
{
}
