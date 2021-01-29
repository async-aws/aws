<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An attempt to return the status of an already completed lifecycle event occurred.
 */
final class LifecycleEventAlreadyCompletedException extends ClientException
{
}
