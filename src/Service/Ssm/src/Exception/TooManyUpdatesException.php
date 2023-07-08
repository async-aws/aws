<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There are concurrent updates for a resource that supports one update at a time.
 */
final class TooManyUpdatesException extends ClientException
{
}
