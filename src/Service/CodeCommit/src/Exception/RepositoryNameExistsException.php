<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified repository name already exists.
 */
final class RepositoryNameExistsException extends ClientException
{
}
