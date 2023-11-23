<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Amazon Web Services KMS throttles requests for the following conditions.
 */
final class KmsThrottledException extends ClientException
{
}
