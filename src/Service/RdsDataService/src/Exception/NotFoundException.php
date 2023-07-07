<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The `resourceArn`, `secretArn`, or `transactionId` value can't be found.
 */
final class NotFoundException extends ClientException
{
}
