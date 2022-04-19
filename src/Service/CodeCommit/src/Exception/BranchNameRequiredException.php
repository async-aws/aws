<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A branch name is required, but was not specified.
 */
final class BranchNameRequiredException extends ClientException
{
}
