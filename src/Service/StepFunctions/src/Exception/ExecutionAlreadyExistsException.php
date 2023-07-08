<?php

namespace AsyncAws\StepFunctions\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The execution has the same `name` as another execution (but a different `input`).
 *
 * > Executions with the same `name` and `input` are considered idempotent.
 */
final class ExecutionAlreadyExistsException extends ClientException
{
}
