<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * One or more specified resources don't exist.
 */
final class ResourceNotFoundException extends ClientException
{
}
