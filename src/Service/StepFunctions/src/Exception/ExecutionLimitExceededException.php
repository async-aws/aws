<?php

namespace AsyncAws\StepFunctions\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The maximum number of running executions has been reached. Running executions must end or be stopped before a new
 * execution can be started.
 */
final class ExecutionLimitExceededException extends ClientException
{
}
