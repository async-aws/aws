<?php

namespace AsyncAws\StepFunctions\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The task token has either expired or the task associated with the token has already been closed.
 */
final class TaskTimedOutException extends ClientException
{
}
