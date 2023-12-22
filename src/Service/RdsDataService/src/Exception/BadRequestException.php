<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There is an error in the call or in a SQL statement. (This error only appears in calls from Aurora Serverless v1
 * databases.).
 */
final class BadRequestException extends ClientException
{
}
