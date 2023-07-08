<?php

namespace AsyncAws\Scheduler\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Updating or deleting the resource can cause an inconsistent state.
 */
final class ConflictException extends ClientException
{
}
