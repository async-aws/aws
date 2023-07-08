<?php

namespace AsyncAws\StepFunctions\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The provided Amazon Resource Name (ARN) is not valid.
 */
final class InvalidArnException extends ClientException
{
}
