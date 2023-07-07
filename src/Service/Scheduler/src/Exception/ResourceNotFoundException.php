<?php

namespace AsyncAws\Scheduler\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request references a resource which does not exist.
 */
final class ResourceNotFoundException extends ClientException
{
}
