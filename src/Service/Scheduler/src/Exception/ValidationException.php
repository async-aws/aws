<?php

namespace AsyncAws\Scheduler\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The input fails to satisfy the constraints specified by an AWS service.
 */
final class ValidationException extends ClientException
{
}
