<?php

namespace AsyncAws\Iot\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An exception thrown when the version of an entity specified with the `expectedVersion` parameter does not match the
 * latest version in the system.
 */
final class VersionConflictException extends ClientException
{
}
