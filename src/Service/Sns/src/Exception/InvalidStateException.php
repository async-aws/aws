<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the specified state is not a valid state for an event source.
 */
final class InvalidStateException extends ClientException
{
}
