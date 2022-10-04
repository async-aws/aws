<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * One or more branch names specified for the trigger is not valid.
 */
final class InvalidRepositoryTriggerBranchNameException extends ClientException
{
}
