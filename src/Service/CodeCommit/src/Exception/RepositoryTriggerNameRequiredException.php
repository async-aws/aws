<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A name for the trigger is required, but was not specified.
 */
final class RepositoryTriggerNameRequiredException extends ClientException
{
}
