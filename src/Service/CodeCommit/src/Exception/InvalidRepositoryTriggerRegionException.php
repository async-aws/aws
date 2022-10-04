<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The AWS Region for the trigger target does not match the AWS Region for the repository. Triggers must be created in
 * the same Region as the target for the trigger.
 */
final class InvalidRepositoryTriggerRegionException extends ClientException
{
}
