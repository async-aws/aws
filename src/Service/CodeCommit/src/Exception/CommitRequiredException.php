<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A commit was not specified.
 */
final class CommitRequiredException extends ClientException
{
}
