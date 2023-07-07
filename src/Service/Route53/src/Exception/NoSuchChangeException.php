<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A change with the specified change ID does not exist.
 */
final class NoSuchChangeException extends ClientException
{
}
