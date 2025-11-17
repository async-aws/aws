<?php

namespace AsyncAws\BedrockAgent\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The number of requests exceeds the service quota. Resubmit your request later.
 */
final class ServiceQuotaExceededException extends ClientException
{
}
