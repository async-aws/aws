<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The number of triggers allowed for the repository was exceeded.
 */
final class MaximumRepositoryTriggersExceededException extends ClientException
{
}
