<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The Amazon Web Services Region for the trigger target does not match the Amazon Web Services Region for the
 * repository. Triggers must be created in the same Amazon Web Services Region as the target for the trigger.
 */
final class InvalidRepositoryTriggerRegionException extends ClientException
{
}
