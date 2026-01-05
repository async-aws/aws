<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Your request exceeds a service quota.
 */
final class ServiceQuotaExceededException extends ClientException
{
}
