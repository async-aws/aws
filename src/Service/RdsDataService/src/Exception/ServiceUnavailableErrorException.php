<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The service specified by the `resourceArn` parameter is not available.
 */
final class ServiceUnavailableErrorException extends ServerException
{
}
