<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The Amazon Resource Name (ARN) for the trigger is not valid for the specified destination. The most common reason for
 * this error is that the ARN does not meet the requirements for the service type.
 */
final class InvalidRepositoryTriggerDestinationArnException extends ClientException
{
}
