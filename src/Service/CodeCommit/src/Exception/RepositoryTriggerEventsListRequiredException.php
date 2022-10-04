<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * At least one event for the trigger is required, but was not specified.
 */
final class RepositoryTriggerEventsListRequiredException extends ClientException
{
}
