<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The result of a Lambda validation function that verifies a lifecycle event is invalid. It should return `Succeeded`
 * or `Failed`.
 */
final class InvalidLifecycleEventHookExecutionStatusException extends ClientException
{
}
