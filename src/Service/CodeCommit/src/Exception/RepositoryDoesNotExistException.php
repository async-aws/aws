<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified repository does not exist.
 */
final class RepositoryDoesNotExistException extends ClientException
{
}
