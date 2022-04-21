<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A specified repository name is not valid.
 *
 * > This exception occurs only when a specified repository name is not valid. Other exceptions occur when a required
 * > repository parameter is missing, or when a specified repository does not exist.
 */
final class InvalidRepositoryNameException extends ClientException
{
}
