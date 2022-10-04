<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * At least one branch name is required, but was not specified in the trigger configuration.
 */
final class RepositoryTriggerBranchNameListRequiredException extends ClientException
{
}
