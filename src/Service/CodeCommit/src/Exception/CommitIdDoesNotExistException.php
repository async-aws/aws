<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified commit ID does not exist.
 */
final class CommitIdDoesNotExistException extends ClientException
{
}
