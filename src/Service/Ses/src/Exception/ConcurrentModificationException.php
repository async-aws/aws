<?php

namespace AsyncAws\Ses\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The resource is being modified by another operation or thread.
 */
final class ConcurrentModificationException extends ServerException
{
}
