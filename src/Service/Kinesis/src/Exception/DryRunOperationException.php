<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the DryRun parameter was specified.
 */
final class DryRunOperationException extends ClientException
{
}
