<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A destination ARN for the target service for the trigger is required, but was not specified.
 */
final class RepositoryTriggerDestinationArnRequiredException extends ClientException
{
}
