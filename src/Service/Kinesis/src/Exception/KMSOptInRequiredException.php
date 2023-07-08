<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The Amazon Web Services access key ID needs a subscription for the service.
 */
final class KMSOptInRequiredException extends ClientException
{
}
