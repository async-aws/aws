<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The number of branches for the trigger was exceeded.
 */
final class MaximumBranchesExceededException extends ClientException
{
}
