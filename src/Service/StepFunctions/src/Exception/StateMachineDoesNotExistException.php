<?php

namespace AsyncAws\StepFunctions\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified state machine does not exist.
 */
final class StateMachineDoesNotExistException extends ClientException
{
}
