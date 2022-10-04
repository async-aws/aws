<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * One or more events specified for the trigger is not valid. Check to make sure that all events specified match the
 * requirements for allowed events.
 */
final class InvalidRepositoryTriggerEventsException extends ClientException
{
}
