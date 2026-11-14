<?php

namespace AsyncAws\Scheduler\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The input fails to satisfy the constraints specified by an Amazon Web Services service.
 */
final class ValidationException extends ClientException
{
}
