<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A lifecycle event hook is invalid. Review the `hooks` section in your AppSpec file to ensure the lifecycle events and
 * `hooks` functions are valid.
 */
final class InvalidLifecycleEventHookExecutionIdException extends ClientException
{
}
