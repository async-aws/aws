<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the requested resource does not exist.
 */
final class NotFoundException extends ClientException
{
}
