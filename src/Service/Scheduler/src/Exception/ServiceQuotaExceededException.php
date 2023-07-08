<?php

namespace AsyncAws\Scheduler\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request exceeds a service quota.
 */
final class ServiceQuotaExceededException extends ClientException
{
}
