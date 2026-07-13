<?php

namespace AsyncAws\Ses\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The resource specified in your request already exists.
 */
final class AlreadyExistsException extends ClientException
{
}
