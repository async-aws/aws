<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified commit does not exist or no commit was specified, and the specified repository has no default branch.
 */
final class CommitDoesNotExistException extends ClientException
{
}
